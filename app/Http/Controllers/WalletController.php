<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    protected $paymongoService;

    public function __construct(PayMongoService $paymongoService)
    {
        $this->paymongoService = $paymongoService;
    }

    /**
     * Display wallet dashboard
     */
    public function index()
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('tutor')->user();
        $userType = Auth::guard('student')->check() ? 'student' : 'tutor';
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'user_type' => $userType,
                'balance' => 0.00,
                'currency' => 'PHP'
            ]);
        }

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Show cash in form
     */
    public function showCashIn()
    {
        return view('wallet.cash-in');
    }

    /**
     * Process cash in request
     */
    public function cashIn(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:50000'
        ]);

        $user = Auth::guard('student')->user() ?? Auth::guard('tutor')->user();
        $userType = Auth::guard('student')->check() ? 'student' : 'tutor';
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        $amount = $request->amount;

        // Create payment intent
        $paymentIntent = $this->paymongoService->createPaymentIntent($amount, [
            'user_id' => $user->id,
            'user_type' => $userType,
            'wallet_id' => $wallet->id,
            'type' => 'cash_in'
        ]);

        if (!$paymentIntent['success']) {
            return back()->with('error', 'Failed to create payment: ' . $paymentIntent['error']);
        }

        // Create GCash source
        $source = $this->paymongoService->createGcashSource($paymentIntent['payment_intent_id'], $amount);

        if (!$source['success']) {
            return back()->with('error', 'Failed to create GCash payment: ' . $source['error']);
        }

        // Create pending transaction
        $transaction = $wallet->transactions()->create([
            'type' => 'cash_in',
            'amount' => $amount,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance,
            'status' => 'pending',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => $paymentIntent['payment_intent_id'],
            'paymongo_source_id' => $source['source_id'],
            'description' => 'Cash in via GCash QR',
            'metadata' => [
                'checkout_url' => $source['checkout_url']
            ]
        ]);

        return view('wallet.payment', [
            'transaction' => $transaction,
            'checkout_url' => $source['checkout_url'],
            'qr_code_url' => $source['qr_code_url']
        ]);
    }

    /**
     * Show cash out form
     */
    public function showCashOut()
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('tutor')->user();
        $userType = Auth::guard('student')->check() ? 'student' : 'tutor';
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        return view('wallet.cash-out', compact('wallet'));
    }

    /**
     * Process cash out request
     */
    public function cashOut(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_number' => 'required|string',
            'account_name' => 'required|string'
        ]);

        $user = Auth::guard('student')->user() ?? Auth::guard('tutor')->user();
        $userType = Auth::guard('student')->check() ? 'student' : 'tutor';
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        if (!$wallet->canAfford($request->amount)) {
            return back()->with('error', 'Insufficient balance.');
        }

        DB::beginTransaction();
        try {
            // Create payout request (simulate PayMongo or similar service)
            $payout = $this->paymongoService->createPayout(
                $request->amount,
                $request->account_number,
                $request->account_name
            );

            if (!$payout['success']) {
                throw new \Exception($payout['error']);
            }

            // Only create a PENDING transaction, no deduction on wallet
            $transaction = $wallet->transactions()->create([
                'type' => 'cash_out',
                'amount' => $request->amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'status' => 'pending',
                'payment_method' => 'gcash',
                'reference_number' => $payout['payout_id'],
                'description' => 'Cash out to GCash: ' . $request->account_number,
                'metadata' => [
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'payout_id' => $payout['payout_id']
                ],
            ]);

            DB::commit();
            return redirect()->route(Auth::guard('student')->check() ? 'student.wallet' : 'tutor.wallet')->with('success', 'Cash out request submitted successfully. It will be processed within 24 hours.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process cash out: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent_id');
        
        if (!$paymentIntentId) {
            return redirect()->route('wallet.index')->with('error', 'Invalid payment callback.');
        }

        // Get payment intent status
        $paymentIntent = $this->paymongoService->getPaymentIntent($paymentIntentId);
        
        if (!$paymentIntent['success']) {
            return redirect()->route('wallet.index')->with('error', 'Failed to verify payment.');
        }

        // Find transaction
        $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            return redirect()->route('wallet.index')->with('error', 'Transaction not found.');
        }

        if ($paymentIntent['status'] === 'succeeded') {
            DB::beginTransaction();
            try {
                // Add funds to wallet
                $transaction->wallet->addFunds($transaction->amount, 'cash_in', [
                    'payment_intent_id' => $paymentIntentId,
                    'verified' => true
                ]);

                $transaction->markAsCompleted();
                
                DB::commit();

                return redirect()->route('wallet.index')->with('success', 'Payment successful! Funds have been added to your wallet.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('wallet.index')->with('error', 'Failed to process payment: ' . $e->getMessage());
            }
        } else {
            $transaction->markAsFailed();
            return redirect()->route('wallet.index')->with('error', 'Payment failed.');
        }
    }

    /**
     * Handle payment failed callback
     */
    public function paymentFailed(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent_id');
        
        if ($paymentIntentId) {
            $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
            if ($transaction) {
                $transaction->markAsFailed();
            }
        }

        return redirect()->route('wallet.index')->with('error', 'Payment was cancelled or failed.');
    }

    /**
     * Get wallet balance (AJAX)
     */
    public function getBalance()
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('tutor')->user();
        $userType = Auth::guard('student')->check() ? 'student' : 'tutor';
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return response()->json(['balance' => 0.00]);
        }

        return response()->json(['balance' => $wallet->balance]);
    }
}