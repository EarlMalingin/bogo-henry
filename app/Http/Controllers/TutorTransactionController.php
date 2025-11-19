<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TutorTransactionController extends Controller
{
    /**
     * Display transaction log with earnings summary
     */
    public function index(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Get tutor's wallet
        $wallet = Wallet::where('user_id', $tutor->id)
            ->where('user_type', 'tutor')
            ->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $tutor->id,
                'user_type' => 'tutor',
                'balance' => 0.00,
                'currency' => 'PHP'
            ]);
        }

        // Get filter parameters
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query
        $query = $wallet->transactions()->orderBy('created_at', 'desc');

        // Apply filters
        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Get paginated transactions
        $transactions = $query->paginate(20)->withQueryString();

        // Calculate earnings summary
        $totalEarnings = $wallet->transactions()
            ->whereIn('type', ['session_earnings', 'assignment_earnings'])
            ->where('status', 'completed')
            ->sum('amount');

        $sessionEarnings = $wallet->transactions()
            ->where('type', 'session_earnings')
            ->where('status', 'completed')
            ->sum('amount');

        $assignmentEarnings = $wallet->transactions()
            ->where('type', 'assignment_earnings')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCashIn = $wallet->transactions()
            ->where('type', 'cash_in')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCashOut = $wallet->transactions()
            ->where('type', 'cash_out')
            ->where('status', 'completed')
            ->sum('amount');

        // Get recent transactions for summary (last 30 days)
        $recentEarnings = $wallet->transactions()
            ->whereIn('type', ['session_earnings', 'assignment_earnings'])
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('amount');

        return view('tutor.transactions.index', compact(
            'tutor',
            'wallet',
            'transactions',
            'totalEarnings',
            'sessionEarnings',
            'assignmentEarnings',
            'totalCashIn',
            'totalCashOut',
            'recentEarnings',
            'typeFilter',
            'statusFilter',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Download transactions as PDF
     */
    public function downloadPdf(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Get tutor's wallet
        $wallet = Wallet::where('user_id', $tutor->id)
            ->where('user_type', 'tutor')
            ->first();

        if (!$wallet) {
            return redirect()->route('tutor.transactions.index')
                ->with('error', 'Wallet not found.');
        }

        // Get filter parameters (same as index)
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query
        $query = $wallet->transactions()->orderBy('created_at', 'desc');

        // Apply filters
        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Get all transactions (no pagination for PDF)
        $transactions = $query->get();

        // Calculate earnings summary
        $totalEarnings = $wallet->transactions()
            ->whereIn('type', ['session_earnings', 'assignment_earnings'])
            ->where('status', 'completed')
            ->sum('amount');

        $sessionEarnings = $wallet->transactions()
            ->where('type', 'session_earnings')
            ->where('status', 'completed')
            ->sum('amount');

        $assignmentEarnings = $wallet->transactions()
            ->where('type', 'assignment_earnings')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCashIn = $wallet->transactions()
            ->where('type', 'cash_in')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCashOut = $wallet->transactions()
            ->where('type', 'cash_out')
            ->where('status', 'completed')
            ->sum('amount');

        // Generate PDF
        $pdf = Pdf::loadView('tutor.transactions.pdf', compact(
            'tutor',
            'wallet',
            'transactions',
            'totalEarnings',
            'sessionEarnings',
            'assignmentEarnings',
            'totalCashIn',
            'totalCashOut',
            'typeFilter',
            'statusFilter',
            'dateFrom',
            'dateTo'
        ));

        // Generate filename
        $filename = 'transaction_log_' . $tutor->tutor_id . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Clear/clean all transactions for the tutor
     */
    public function clean(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Get tutor's wallet
        $wallet = Wallet::where('user_id', $tutor->id)
            ->where('user_type', 'tutor')
            ->first();

        if (!$wallet) {
            return redirect()->route('tutor.transactions.index')
                ->with('error', 'Wallet not found.');
        }

        // Get filter parameters to apply same filters when cleaning
        $typeFilter = $request->get('type', 'all');
        $statusFilter = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query to get transactions to delete
        $query = $wallet->transactions();

        // Apply filters
        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Count transactions before deletion
        $count = $query->count();

        // Delete filtered transactions
        $query->delete();

        return redirect()->route('tutor.transactions.index')
            ->with('success', "Successfully cleaned {$count} transaction(s) from your log.");
    }
}
