<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\AuditLog;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class SecureWalletController extends Controller
{
    protected $paymongoService;

    public function __construct(PayMongoService $paymongoService)
    {
        $this->paymongoService = $paymongoService;
        
        // Note: Rate limiting is applied in routes, not here
        // This prevents the middleware() method error
    }

    /**
     * Helper method to get authenticated user and type based on route
     * This prevents guard conflicts when same user has both roles
     */
    private function getAuthenticatedUser()
    {
        $routeName = request()->route()->getName();
        
        if (str_contains($routeName, 'student')) {
            if (!Auth::guard('student')->check()) {
                abort(403, 'Unauthorized');
            }
            return [
                'user' => Auth::guard('student')->user(),
                'userType' => 'student'
            ];
        } elseif (str_contains($routeName, 'tutor')) {
            if (!Auth::guard('tutor')->check()) {
                abort(403, 'Unauthorized');
            }
            return [
                'user' => Auth::guard('tutor')->user(),
                'userType' => 'tutor'
            ];
        } else {
            abort(403, 'Unauthorized route');
        }
    }

    /**
     * Display wallet dashboard
     */
    public function index()
    {
        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
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
            
            $this->logAudit('wallet_created', [
                'wallet_id' => $wallet->id,
                'initial_balance' => 0.00
            ]);
        }

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.index', compact('wallet', 'transactions', 'userType', 'user'));
    }

    /**
     * Show cash in form
     */
    public function showCashIn()
    {
        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'user_type' => $userType],
            ['balance' => 0.00, 'currency' => 'PHP']
        );

        $this->logAudit('view_cash_in_form', $user);
        return view('wallet.cash-in', compact('wallet', 'userType', 'user'));
    }

    /**
     * Process internal cash in (direct balance addition)
     * Note: This should be restricted to admin users only in production
     */
    public function internalCashIn(Request $request)
    {
        try {
            // Enhanced validation with stricter rules
            $request->validate([
                'amount' => 'required|numeric|min:0.01|max:50000|regex:/^\d+(\.\d{1,2})?$/'
            ], [
                'amount.regex' => 'Amount must be a valid number with maximum 2 decimal places.',
                'amount.min' => 'Minimum cash-in amount is ₱0.01.',
                'amount.max' => 'Maximum cash-in amount is ₱50,000 per transaction.'
            ]);

            $auth = $this->getAuthenticatedUser();
            $user = $auth['user'];
            $userType = $auth['userType'];
            
            // Check daily limits
            if (!$this->checkDailyLimits($user, $userType, 'cash_in', $request->amount)) {
                return back()->with('error', 'Daily cash-in limit exceeded. Maximum ₱100,000 per day.');
            }

            // Check rate limiting
            $key = 'internal_cash_in:' . $user->id . ':' . $userType;
            if (RateLimiter::tooManyAttempts($key, 10)) {
                $seconds = RateLimiter::availableIn($key);
                return back()->with('error', "Too many cash-in attempts. Please try again in {$seconds} seconds.");
            }

            RateLimiter::hit($key, 60); // 1 minute decay

            $wallet = Wallet::where('user_id', $user->id)
                ->where('user_type', $userType)
                ->first();

            if (!$wallet) {
                return back()->with('error', 'Wallet not found.');
            }

            $amount = round($request->amount, 2);

            // Start database transaction
            DB::beginTransaction();

            try {
                // Update wallet balance
                $oldBalance = $wallet->balance;
                $newBalance = $oldBalance + $amount;
                
                $wallet->update([
                    'balance' => $newBalance
                ]);

                // Create transaction record
                $transaction = $wallet->transactions()->create([
                    'type' => 'cash_in',
                    'amount' => $amount,
                    'balance_before' => $oldBalance,
                    'balance_after' => $newBalance,
                    'status' => 'completed',
                    'payment_method' => 'internal',
                    'reference_number' => 'INT' . time() . rand(1000, 9999),
                    'description' => 'Internal cash-in (Demo)',
                    'metadata' => [
                        'method' => 'internal_direct',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]
                ]);

                // Log audit
                $this->logAudit('internal_cash_in_completed', [
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance
                ]);

                DB::commit();

                return back()->with('success', "Successfully added ₱" . number_format($amount, 2) . " to your wallet! Your new balance is ₱" . number_format($newBalance, 2));

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Internal cash-in process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'amount' => $request->amount ?? 'unknown'
            ]);
            
            return back()->with('error', 'An error occurred while processing your cash-in. Please try again.');
        }
    }

    /**
     * Process cash in request with enhanced security
     */
    public function cashIn(Request $request)
    {
        try {
            // Enhanced validation with stricter rules
            $request->validate([
                'amount' => 'required|numeric|min:0.01|max:50000|regex:/^\d+(\.\d{1,2})?$/'
            ], [
                'amount.regex' => 'Amount must be a valid number with maximum 2 decimal places.',
                'amount.min' => 'Minimum cash-in amount is ₱0.01.',
                'amount.max' => 'Maximum cash-in amount is ₱50,000 per transaction.'
            ]);

        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
        // Check daily limits
        if (!$this->checkDailyLimits($user, $userType, 'cash_in', $request->amount)) {
            return back()->with('error', 'Daily cash-in limit exceeded. Maximum ₱100,000 per day.');
        }

        // Check rate limiting
        $key = 'cash_in:' . $user->id . ':' . $userType;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many cash-in attempts. Please try again in {$seconds} seconds.");
        }

        RateLimiter::hit($key, 60); // 1 minute decay

        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        $amount = round($request->amount, 2); // Ensure proper decimal handling

        // Check payment method
        $paymentMethod = $request->input('payment_method', 'gcash');
        $isInternalCashIn = $request->has('internal_cash_in') || $request->routeIs('*.internal-cash-in') || $paymentMethod === 'internal';
        
        // For internal cash-in requests only, add money directly
        if ($isInternalCashIn) {
            Log::info('Using internal cash-in (direct)', ['amount' => $amount, 'payment_method' => $paymentMethod]);
            
            DB::beginTransaction();
            try {
                $oldBalance = $wallet->balance;
                $newBalance = $oldBalance + $amount;
                
                $wallet->update([
                    'balance' => $newBalance
                ]);

                $transaction = $wallet->transactions()->create([
                    'type' => 'cash_in',
                    'amount' => $amount,
                    'balance_before' => $oldBalance,
                    'balance_after' => $newBalance,
                    'status' => 'completed',
                    'payment_method' => 'internal',
                    'reference_number' => 'INT' . time() . rand(1000, 9999),
                    'description' => 'Internal cash-in (Direct)',
                    'metadata' => [
                        'method' => 'internal_direct',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]
                ]);

                $this->logAudit('internal_cash_in_completed', [
                    'amount' => $amount,
                    'transaction_id' => $transaction->id,
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance
                ]);

                DB::commit();

                return back()->with('success', "Successfully added ₱" . number_format($amount, 2) . " to your wallet! Your new balance is ₱" . number_format($newBalance, 2));

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        // For all GCash payments, use PayMongo payment gateway
        Log::info('Using PayMongo for GCash payment', ['amount' => $amount]);
        
        // For small amounts, use simulated GCash payment (PayMongo minimum is ₱2,000)
        if ($amount < 2000) {
            Log::info('Using simulated GCash payment for small amount', ['amount' => $amount]);
            
            // Create a simulated payment intent for small amounts
            $simulatedPaymentIntent = [
                'success' => true,
                'payment_intent_id' => 'SIM_' . time() . '_' . rand(1000, 9999),
                'amount' => $amount,
                'currency' => 'PHP',
                'status' => 'awaiting_payment_method'
            ];
            
            // Create a simulated GCash source
            $simulatedSource = [
                'success' => true,
                'source_id' => 'SIM_SRC_' . time() . '_' . rand(1000, 9999),
                'checkout_url' => route('wallet.payment.success') . '?payment_intent_id=' . $simulatedPaymentIntent['payment_intent_id'] . '&simulated=true',
                'qr_code_url' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="200" height="200" fill="#0070ba"/><text x="100" y="100" text-anchor="middle" fill="white" font-family="Arial" font-size="14">GCash QR</text><text x="100" y="120" text-anchor="middle" fill="white" font-family="Arial" font-size="12">₱' . number_format($amount, 2) . '</text></svg>')
            ];
            
            // Create pending approval transaction (requires payment proof upload)
            $transaction = $wallet->transactions()->create([
                'type' => 'cash_in',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance,
                'status' => 'pending_approval',
                'payment_method' => 'gcash',
                'paymongo_payment_intent_id' => $simulatedPaymentIntent['payment_intent_id'],
                'paymongo_source_id' => $simulatedSource['source_id'],
                'description' => 'Cash in via GCash QR (Simulated) - Payment proof required',
                'metadata' => [
                    'checkout_url' => $simulatedSource['checkout_url'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'simulated' => true,
                    'requires_payment_proof' => true
                ]
            ]);

            $this->logAudit('simulated_gcash_payment_initiated', [
                'amount' => $amount,
                'transaction_id' => $transaction->id,
                'payment_intent_id' => $simulatedPaymentIntent['payment_intent_id'],
                'source_id' => $simulatedSource['source_id']
            ]);

            return view('wallet.payment', [
                'transaction' => $transaction,
                'checkout_url' => $simulatedSource['checkout_url'],
                'qr_code_url' => $simulatedSource['qr_code_url'],
                'userType' => $userType,
                'user' => $user
            ]);
        }
        
        // For amounts ₱2,000 and above, use real PayMongo
        $paymentIntent = $this->paymongoService->createPaymentIntent($amount, [
            'user_id' => (string) $user->id,
            'user_type' => $userType,
            'wallet_id' => (string) $wallet->id,
            'type' => 'cash_in',
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 100), // Limit length
            'timestamp' => now()->toISOString()
        ]);

        Log::info('Payment intent result', ['success' => $paymentIntent['success'], 'data' => $paymentIntent]);

        if (!$paymentIntent['success']) {
            $this->logAudit('cash_in_failed', [
                'amount' => $amount,
                'error' => $paymentIntent['error'],
                'reason' => 'payment_intent_creation_failed'
            ]);
            
            Log::error('Payment intent creation failed', ['error' => $paymentIntent['error']]);
            return back()->with('error', 'Failed to create payment: ' . $paymentIntent['error']);
        }

        // Create GCash source
        Log::info('Creating GCash source', ['payment_intent_id' => $paymentIntent['payment_intent_id'], 'amount' => $amount]);
        
        $source = $this->paymongoService->createGcashSource($paymentIntent['payment_intent_id'], $amount);

        Log::info('GCash source result', ['success' => $source['success'], 'data' => $source]);

        if (!$source['success']) {
            $this->logAudit('cash_in_failed', [
                'amount' => $amount,
                'payment_intent_id' => $paymentIntent['payment_intent_id'],
                'error' => $source['error'],
                'reason' => 'source_creation_failed'
            ]);
            
            Log::error('GCash source creation failed', ['error' => $source['error']]);
            return back()->with('error', 'Failed to create GCash payment: ' . $source['error']);
        }

        // Create pending approval transaction (requires payment proof upload)
        $transaction = $wallet->transactions()->create([
            'type' => 'cash_in',
            'amount' => $amount,
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance,
            'status' => 'pending_approval',
            'payment_method' => 'gcash',
            'paymongo_payment_intent_id' => $paymentIntent['payment_intent_id'],
            'paymongo_source_id' => $source['source_id'],
            'description' => 'Cash in via GCash QR - Payment proof required',
            'metadata' => [
                'checkout_url' => $source['checkout_url'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'requires_payment_proof' => true
            ]
        ]);

        $this->logAudit('cash_in_initiated', [
            'amount' => $amount,
            'transaction_id' => $transaction->id,
            'payment_intent_id' => $paymentIntent['payment_intent_id'],
            'source_id' => $source['source_id']
        ]);

        return view('wallet.payment', [
            'transaction' => $transaction,
            'checkout_url' => $source['checkout_url'],
            'qr_code_url' => $source['qr_code_url'],
            'userType' => $userType,
            'user' => $user
        ]);
        
        } catch (\Exception $e) {
            Log::error('Cash-in process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'amount' => $request->amount ?? 'unknown'
            ]);
            
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    /**
     * Show cash out form
     */
    public function showCashOut()
    {
        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        return view('wallet.cash-out', compact('wallet', 'userType', 'user'));
    }

    /**
     * Process cash out request with enhanced security
     */
    public function cashOut(Request $request)
    {
        // Enhanced validation with strict rules
        $request->validate([
            'amount' => 'required|numeric|min:1|max:50000|regex:/^\d+(\.\d{1,2})?$/',
            'account_number' => 'required|string|regex:/^[0-9]{10,12}$/',
            'account_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\.\-\']+$/'
        ], [
            'amount.regex' => 'Amount must be a valid number with maximum 2 decimal places.',
            'account_number.regex' => 'Account number must be 10-12 digits.',
            'account_name.regex' => 'Account name can only contain letters, spaces, dots, hyphens, and apostrophes.'
        ]);

        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
        // Check daily limits
        if (!$this->checkDailyLimits($user, $userType, 'cash_out', $request->amount)) {
            return back()->with('error', 'Daily cash-out limit exceeded. Maximum ₱100,000 per day.');
        }

        // Check rate limiting
        $key = 'cash_out:' . $user->id . ':' . $userType;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many cash-out attempts. Please try again in {$seconds} seconds.");
        }

        RateLimiter::hit($key, 300); // 5 minutes decay

        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return back()->with('error', 'Wallet not found.');
        }

        if (!$wallet->canAfford($request->amount)) {
            $this->logAudit('cash_out_failed', [
                'amount' => $request->amount,
                'reason' => 'insufficient_balance',
                'current_balance' => $wallet->balance
            ]);
            
            return back()->with('error', 'Insufficient balance.');
        }

        // Sanitize inputs
        $accountNumber = preg_replace('/[^0-9]/', '', $request->account_number);
        $accountName = trim(strip_tags($request->account_name));
        $amount = round($request->amount, 2);

        DB::beginTransaction();
        try {
            // Create payout request
            $payout = $this->paymongoService->createPayout(
                $amount,
                $accountNumber,
                $accountName
            );

            if (!$payout['success']) {
                throw new \Exception($payout['error']);
            }

            // Create pending transaction instead of removing funds
            $transaction = $wallet->transactions()->create([
                'type' => 'cash_out',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance, // no deduction yet
                'status' => 'pending',
                'payment_method' => 'gcash',
                'reference_number' => $payout['payout_id'],
                'description' => 'Cash out to GCash: ' . $accountNumber,
                'metadata' => [
                    'account_number' => $accountNumber,
                    'account_name' => $accountName,
                    'payout_id' => $payout['payout_id'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);

            if (!$transaction) {
                throw new \Exception('Failed to process cash out.');
            }

            $this->logAudit('cash_out_initiated', [
                'amount' => $amount,
                'transaction_id' => $transaction->id,
                'account_number' => substr($accountNumber, 0, 4) . '****' . substr($accountNumber, -4), // Masked
                'account_name' => $accountName,
                'payout_id' => $payout['payout_id']
            ]);

            DB::commit();
            return redirect()->route($userType . '.wallet')->with('success', 'Cash out request submitted successfully. It will be processed within 24 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->logAudit('cash_out_failed', [
                'amount' => $amount,
                'error' => $e->getMessage(),
                'account_number' => substr($accountNumber, 0, 4) . '****' . substr($accountNumber, -4)
            ]);
            
            return back()->with('error', 'Failed to process cash out: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment success callback with webhook verification
     */
    public function paymentSuccess(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent_id');
        $isSimulated = $request->get('simulated', false);
        
        if (!$paymentIntentId) {
            $this->logAudit('payment_callback_failed', [
                'reason' => 'missing_payment_intent_id',
                'ip_address' => $request->ip()
            ]);
            
            // Try to get userType from session or default to student
            if (Auth::guard('student')->check()) {
                return redirect()->route('student.wallet')->with('error', 'Invalid payment callback.');
            } elseif (Auth::guard('tutor')->check()) {
                return redirect()->route('tutor.wallet')->with('error', 'Invalid payment callback.');
            }
            return redirect('/login')->with('error', 'Please login to continue.');
        }

        // Handle simulated payments (small amounts)
        if ($isSimulated || strpos($paymentIntentId, 'SIM_') === 0) {
            $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->with('wallet')->first();
            
            if (!$transaction) {
                if (Auth::guard('student')->check()) {
                    return redirect()->route('student.wallet')->with('error', 'Transaction not found.');
                } elseif (Auth::guard('tutor')->check()) {
                    return redirect()->route('tutor.wallet')->with('error', 'Transaction not found.');
                }
                return redirect('/login')->with('error', 'Please login to continue.');
            }

            // Get userType from the transaction's wallet
            $userType = $transaction->wallet->user_type;

            // Don't auto-complete simulated payments - they need admin approval with payment proof
            // Just mark that payment was initiated/verified
            if ($transaction->status === 'pending_approval') {
                $transaction->update([
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'payment_verified' => true,
                        'payment_verified_at' => now()->toISOString()
                    ])
                ]);

                $this->logAudit('simulated_payment_verified', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'payment_intent_id' => $paymentIntentId
                ]);

                return redirect()->route($userType . '.wallet')->with('success', 'Payment verified! Please upload your payment proof and wait for admin approval.');
            }
            
            // If already completed or failed, just redirect
            return redirect()->route($userType . '.wallet');
        }

        // Verify webhook signature if provided
        if ($request->hasHeader('Paymongo-Signature')) {
            if (!$this->verifyWebhookSignature($request)) {
                $this->logAudit('payment_callback_failed', [
                    'reason' => 'invalid_webhook_signature',
                    'payment_intent_id' => $paymentIntentId,
                    'ip_address' => $request->ip()
                ]);
                
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        }

        // Get payment intent status
        $paymentIntent = $this->paymongoService->getPaymentIntent($paymentIntentId);
        
        if (!$paymentIntent['success']) {
            $this->logAudit('payment_verification_failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $paymentIntent['error']
            ]);
            
            if (Auth::guard('student')->check()) {
                return redirect()->route('student.wallet')->with('error', 'Failed to verify payment.');
            } elseif (Auth::guard('tutor')->check()) {
                return redirect()->route('tutor.wallet')->with('error', 'Failed to verify payment.');
            }
            return redirect('/login')->with('error', 'Please login to continue.');
        }

        // Find transaction
        $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->with('wallet')->first();
        
        if (!$transaction) {
            $this->logAudit('payment_callback_failed', [
                'reason' => 'transaction_not_found',
                'payment_intent_id' => $paymentIntentId
            ]);
            
            if (Auth::guard('student')->check()) {
                return redirect()->route('student.wallet')->with('error', 'Transaction not found.');
            } elseif (Auth::guard('tutor')->check()) {
                return redirect()->route('tutor.wallet')->with('error', 'Transaction not found.');
            }
            return redirect('/login')->with('error', 'Please login to continue.');
        }
        
        // Get userType from the transaction's wallet
        $userType = $transaction->wallet->user_type;

        if ($paymentIntent['status'] === 'succeeded') {
            DB::beginTransaction();
            try {
                // Update transaction to pending admin approval instead of adding funds immediately
                $transaction->update([
                    'status' => 'pending_approval',
                    'reference_number' => $paymentIntentId,
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'payment_intent_id' => $paymentIntentId,
                        'verified' => true,
                        'webhook_verified' => $request->hasHeader('Paymongo-Signature'),
                        'approval_requested_at' => now()
                    ])
                ]);
                
                $this->logAudit('payment_pending_approval', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'payment_intent_id' => $paymentIntentId,
                    'user_id' => $transaction->wallet->user_id,
                    'user_type' => $transaction->wallet->user_type
                ]);
                
                DB::commit();

                return redirect()->route($userType . '.wallet')->with('success', 'Payment received! Your transaction is pending admin approval and will be processed within 24 hours.');
            } catch (\Exception $e) {
                DB::rollBack();
                
                $this->logAudit('payment_processing_failed', [
                    'transaction_id' => $transaction->id,
                    'payment_intent_id' => $paymentIntentId,
                    'error' => $e->getMessage()
                ]);
                
                return redirect()->route('wallet.index')->with('error', 'Failed to process payment: ' . $e->getMessage());
            }
        } else {
            $transaction->markAsFailed();
            
            $this->logAudit('payment_failed', [
                'transaction_id' => $transaction->id,
                'payment_intent_id' => $paymentIntentId,
                'status' => $paymentIntent['status']
            ]);
            
            return redirect()->route($userType . '.wallet')->with('error', 'Payment failed.');
        }
    }

    /**
     * Handle payment failed callback
     */
    public function paymentFailed(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent_id');
        
        $userType = null;
        if ($paymentIntentId) {
            $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->with('wallet')->first();
            if ($transaction) {
                $transaction->markAsFailed();
                // Get userType from the transaction's wallet
                $userType = $transaction->wallet->user_type;
                
                $this->logAudit('payment_cancelled', [
                    'transaction_id' => $transaction->id,
                    'payment_intent_id' => $paymentIntentId
                ]);
            }
        }

        // Fallback to auth guards if userType not found
        if (!$userType) {
            if (Auth::guard('student')->check()) {
                return redirect()->route('student.wallet')->with('error', 'Payment was cancelled or failed.');
            } elseif (Auth::guard('tutor')->check()) {
                return redirect()->route('tutor.wallet')->with('error', 'Payment was cancelled or failed.');
            }
            return redirect('/login')->with('error', 'Please login to continue.');
        }
        
        return redirect()->route($userType . '.wallet')->with('error', 'Payment was cancelled or failed.');
    }

    /**
     * Get wallet balance (AJAX) with rate limiting
     */
    public function getBalance()
    {
        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return response()->json(['balance' => 0.00]);
        }

        return response()->json(['balance' => $wallet->balance]);
    }

    /**
     * ADMIN DEBUG: Output all user_ids which have both student and tutor wallet entries
     */
    public function auditDuplicateWallets()
    {
        $studentWallets = Wallet::where('user_type', 'student')->pluck('user_id')->toArray();
        $tutorWallets = Wallet::where('user_type', 'tutor')->pluck('user_id')->toArray();
        $duplicateIds = array_intersect($studentWallets, $tutorWallets);
        $results = [];
        foreach ($duplicateIds as $uid) {
            $results[] = [
                'user_id' => $uid,
                'student_wallet' => Wallet::where('user_id', $uid)->where('user_type', 'student')->first(),
                'tutor_wallet' => Wallet::where('user_id', $uid)->where('user_type', 'tutor')->first(),
            ];
        }
        return response()->json($results);
    }

    /**
     * Check daily transaction limits
     */
    private function checkDailyLimits($user, $userType, $type, $amount)
    {
        // Get the user's wallet first
        $wallet = Wallet::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if (!$wallet) {
            return true; // No wallet means no previous transactions
        }

        $todayTransactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->where('type', $type)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        $dailyLimit = 100000; // ₱100,000 daily limit
        
        return ($todayTransactions + $amount) <= $dailyLimit;
    }

    /**
     * Verify PayMongo webhook signature
     */
    private function verifyWebhookSignature(Request $request)
    {
        $signature = $request->header('Paymongo-Signature');
        $payload = $request->getContent();
        $webhookSecret = config('services.paymongo.webhook_secret');
        
        if (!$signature || !$webhookSecret) {
            return false;
        }
        
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Log audit events
     */
    private function logAudit($action, $details = [])
    {
        try {
            // Try to get authenticated user from context
            $user = null;
            $userType = null;
            
            // Check if we're in a request context with authenticated users
            if (Auth::guard('student')->check()) {
                $user = Auth::guard('student')->user();
                $userType = 'student';
            } elseif (Auth::guard('tutor')->check()) {
                $user = Auth::guard('tutor')->user();
                $userType = 'tutor';
            } elseif (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                $userType = 'admin';
            }
            
            // If we have user data in details (like in uploadPaymentProof), use that
            if (!$user && isset($details['user_id']) && isset($details['user_type'])) {
                AuditLog::create([
                    'user_id' => $details['user_id'],
                    'user_type' => $details['user_type'],
                    'action' => $action,
                    'details' => $details,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                return;
            }
            
            // Only create log if we have user info
            if ($user && $userType) {
                AuditLog::create([
                    'user_id' => $user->id,
                    'user_type' => $userType,
                    'action' => $action,
                    'details' => $details,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create audit log', [
                'action' => $action,
                'details' => $details,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle payment proof upload
     */
    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:wallet_transactions,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'description' => 'nullable|string|max:500'
        ]);

        $auth = $this->getAuthenticatedUser();
        $user = $auth['user'];
        $userType = $auth['userType'];

        $transaction = WalletTransaction::with('wallet')->findOrFail($request->transaction_id);

        // Verify transaction belongs to current user
        if ($transaction->wallet->user_id !== $user->id || $transaction->wallet->user_type !== $userType) {
            return back()->with('error', 'Unauthorized access to transaction.');
        }

        // Verify transaction is in pending approval status (or pending for backward compatibility)
        if (!in_array($transaction->status, ['pending_approval', 'pending'])) {
            return back()->with('error', 'Transaction is not in pending approval status.');
        }

        DB::beginTransaction();
        try {
            // Store the payment proof image
            $imagePath = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update transaction with payment proof and ensure status is pending_approval
            $transaction->update([
                'payment_proof_path' => $imagePath,
                'payment_proof_description' => $request->description,
                'payment_proof_uploaded_at' => now(),
                'status' => 'pending_approval' // Ensure status is pending_approval for admin review
            ]);

            // Refresh the model to ensure we have the latest data
            $transaction->refresh();

            $this->logAudit('payment_proof_uploaded', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'user_id' => $user->id,
                'user_type' => $userType,
                'proof_path' => $imagePath
            ]);

            DB::commit();

            return redirect()->route($userType . '.wallet')->with('success', 'Payment proof uploaded successfully! Your transaction is now pending admin review.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->logAudit('payment_proof_upload_failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return back()->with('error', 'Failed to upload payment proof: ' . $e->getMessage());
        }
    }
}
