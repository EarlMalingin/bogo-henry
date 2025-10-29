<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\DB;
use App\Services\PayMongoService;

class AdminWalletController extends Controller
{
    protected $paymongoService;

    public function __construct(PayMongoService $paymongoService)
    {
        $this->paymongoService = $paymongoService;
    }

    /**
     * Display admin wallet dashboard
     */
    public function index()
    {
        // Get wallet statistics
        $totalWallets = Wallet::count();
        $totalBalance = Wallet::sum('balance');
        
        // Get transaction statistics
        $totalTransactions = WalletTransaction::count();
        $pendingPayouts = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'pending')
            ->count();
        $pendingCashIns = WalletTransaction::where('type', 'cash_in')
            ->where('status', 'pending_approval')
            ->count();
        $completedPayouts = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'completed')
            ->count();
        $totalCashIn = WalletTransaction::where('type', 'cash_in')
            ->where('status', 'completed')
            ->sum('amount');
        $totalCashOut = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'completed')
            ->sum('amount');

        // Get recent transactions
        $recentTransactions = WalletTransaction::with(['wallet'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get pending payout requests
        $pendingPayoutRequests = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'pending')
            ->with(['wallet'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.wallet.index', compact(
            'totalWallets',
            'totalBalance',
            'totalTransactions',
            'pendingPayouts',
            'pendingCashIns',
            'completedPayouts',
            'totalCashIn',
            'totalCashOut',
            'recentTransactions',
            'pendingPayoutRequests'
        ));
    }

    /**
     * Show all transactions
     */
    public function transactions(Request $request)
    {
        $query = WalletTransaction::with(['wallet']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.wallet.transactions', compact('transactions'));
    }

    /**
     * Show pending payouts
     */
    public function pendingPayouts()
    {
        $pendingPayouts = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'pending')
            ->with(['wallet'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.wallet.pending-payouts', compact('pendingPayouts'));
    }

    /**
     * Approve a payout
     */
    public function approvePayout(Request $request, $id)
    {
        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->type !== 'cash_out' || $transaction->status !== 'pending') {
            return back()->with('error', 'Invalid payout request.');
        }

        DB::beginTransaction();
        try {
            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::guard('admin')->id()
            ]);

            DB::commit();

            return back()->with('success', 'Payout approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve payout: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payout
     */
    public function rejectPayout(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->type !== 'cash_out' || $transaction->status !== 'pending') {
            return back()->with('error', 'Invalid payout request.');
        }

        DB::beginTransaction();
        try {
            // Refund the amount back to wallet
            $transaction->wallet->addFunds($transaction->amount, 'refund', [
                'original_transaction_id' => $transaction->id,
                'rejection_reason' => $request->reason
            ]);

            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'processed_at' => now(),
                'processed_by' => Auth::guard('admin')->id(),
                'failure_reason' => $request->reason
            ]);

            DB::commit();

            return back()->with('success', 'Payout rejected and funds refunded.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject payout: ' . $e->getMessage());
        }
    }

    /**
     * Show user wallets
     */
    public function userWallets(Request $request)
    {
        $query = Wallet::with(['transactions']);

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        $wallets = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.wallet.user-wallets', compact('wallets'));
    }

    /**
     * Show wallet details for a specific user
     */
    public function showUserWallet($userType, $userId)
    {
        $wallet = Wallet::where('user_id', $userId)
            ->where('user_type', $userType)
            ->with(['transactions' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        // Get user details
        if ($userType === 'student') {
            $user = Student::findOrFail($userId);
        } else {
            $user = Tutor::findOrFail($userId);
        }

        return view('admin.wallet.user-wallet-detail', compact('wallet', 'user'));
    }

    /**
     * Show pending cash-in requests
     */
    public function pendingCashIns()
    {
        $pendingCashIns = WalletTransaction::where('type', 'cash_in')
            ->whereIn('status', ['pending_approval', 'pending']) // Also include 'pending' for backward compatibility
            ->with(['wallet'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Refresh each transaction to ensure we have the latest payment proof data
        foreach ($pendingCashIns as $transaction) {
            $transaction->refresh();
        }

        return view('admin.wallet.pending-cash-ins', compact('pendingCashIns'));
    }

    /**
     * Approve a cash-in transaction
     */
    public function approveCashIn(Request $request, $id)
    {
        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->type !== 'cash_in' || !in_array($transaction->status, ['pending_approval', 'pending'])) {
            return back()->with('error', 'Invalid cash-in request.');
        }

        DB::beginTransaction();
        try {
            // Add funds to wallet
            $transaction->wallet->addFunds($transaction->amount, 'cash_in', [
                'payment_intent_id' => $transaction->metadata['payment_intent_id'] ?? null,
                'verified' => true,
                'admin_approved' => true,
                'approved_by' => Auth::guard('admin')->id(),
                'approved_at' => now()
            ]);

            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::guard('admin')->id(),
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'admin_approved' => true,
                    'approved_by' => Auth::guard('admin')->id(),
                    'approved_at' => now()
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Cash-in approved successfully. Funds have been added to the user\'s wallet.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve cash-in: ' . $e->getMessage());
        }
    }

    /**
     * Reject a cash-in transaction
     */
    public function rejectCashIn(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->type !== 'cash_in' || !in_array($transaction->status, ['pending_approval', 'pending'])) {
            return back()->with('error', 'Invalid cash-in request.');
        }

        DB::beginTransaction();
        try {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'processed_at' => now(),
                'processed_by' => Auth::guard('admin')->id(),
                'failure_reason' => $request->reason,
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'admin_rejected' => true,
                    'rejected_by' => Auth::guard('admin')->id(),
                    'rejected_at' => now(),
                    'rejection_reason' => $request->reason
                ])
            ]);

            DB::commit();

            return back()->with('success', 'Cash-in rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject cash-in: ' . $e->getMessage());
        }
    }
    public function manualTransaction(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_type' => 'required|in:student,tutor',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,deduct',
            'description' => 'required|string|max:255'
        ]);

        $wallet = Wallet::where('user_id', $request->user_id)
            ->where('user_type', $request->user_type)
            ->first();

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        DB::beginTransaction();
        try {
            if ($request->type === 'add') {
                $transaction = $wallet->addFunds($request->amount, 'manual_add', [
                    'description' => $request->description,
                    'processed_by' => Auth::guard('admin')->id()
                ]);
            } else {
                if (!$wallet->canAfford($request->amount)) {
                    return back()->with('error', 'Insufficient balance.');
                }
                
                $transaction = $wallet->deductFunds($request->amount, 'manual_deduct', [
                    'description' => $request->description,
                    'processed_by' => Auth::guard('admin')->id()
                ]);
            }

            $transaction->update([
                'status' => 'completed',
                'processed_at' => now(),
                'processed_by' => Auth::guard('admin')->id()
            ]);

            DB::commit();

            return back()->with('success', 'Manual transaction processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process transaction: ' . $e->getMessage());
        }
    }

    /**
     * Upload payment proof for a cash-in transaction (Admin)
     */
    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'description' => 'nullable|string|max:500'
        ]);

        $transaction = WalletTransaction::findOrFail($id);

        if ($transaction->type !== 'cash_in' || !in_array($transaction->status, ['pending_approval', 'pending'])) {
            return back()->with('error', 'Invalid cash-in request or transaction is not in pending approval status.');
        }

        DB::beginTransaction();
        try {
            // Store the payment proof image
            $imagePath = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update transaction with payment proof
            $transaction->update([
                'payment_proof_path' => $imagePath,
                'payment_proof_description' => $request->description,
                'payment_proof_uploaded_at' => now(),
                'status' => 'pending_approval' // Keep as pending for admin review
            ]);

            // Refresh the model to ensure we have the latest data
            $transaction->refresh();

            DB::commit();

            return back()->with('success', 'Payment proof uploaded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to upload payment proof: ' . $e->getMessage());
        }
    }
}
