<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $paymongoService;

    public function __construct(PayMongoService $paymongoService)
    {
        $this->paymongoService = $paymongoService;
    }

    /**
     * Handle PayMongo webhook events
     */
    public function handlePayMongoWebhook(Request $request)
    {
        // Log all webhook requests for debugging
        Log::info('PayMongo webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all()
        ]);

        // Verify webhook signature for security
        // Note: Ensure PAYMONGO_WEBHOOK_SECRET is set in .env for production
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid webhook signature received');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        $eventType = $payload['data']['type'] ?? null;

        Log::info('PayMongo webhook received', [
            'event_type' => $eventType,
            'payload' => $payload
        ]);

        try {
            switch ($eventType) {
                case 'source.chargeable':
                    $this->handleSourceChargeable($payload);
                    break;
                
                case 'payment.paid':
                    $this->handlePaymentPaid($payload);
                    break;
                
                case 'payment.failed':
                    $this->handlePaymentFailed($payload);
                    break;
                
                default:
                    Log::info('Unhandled webhook event type: ' . $eventType);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle source.chargeable event
     */
    private function handleSourceChargeable($payload)
    {
        $sourceId = $payload['data']['id'];
        
        // Find transaction by source ID
        $transaction = WalletTransaction::where('paymongo_source_id', $sourceId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for source ID: ' . $sourceId);
            return;
        }

        // Update transaction status
        $transaction->update(['status' => 'processing']);
        
        Log::info('Source chargeable processed', ['transaction_id' => $transaction->id]);
    }

    /**
     * Handle payment.paid event
     */
    private function handlePaymentPaid($payload)
    {
        // PayMongo webhook structure for payment.paid
        $paymentIntentId = $payload['data']['id'] ?? null;
        
        if (!$paymentIntentId) {
            Log::warning('Payment intent ID not found in payload', ['payload' => $payload]);
            return;
        }

        // Find transaction by payment intent ID
        $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for payment intent ID: ' . $paymentIntentId);
            return;
        }

        DB::beginTransaction();
        try {
            // Update transaction to pending admin approval instead of adding funds immediately
            $transaction->update([
                'status' => 'pending_approval',
                'reference_number' => $paymentIntentId,
                'metadata' => array_merge($transaction->metadata ?? [], [
                    'payment_intent_id' => $paymentIntentId,
                    'webhook_verified' => true,
                    'webhook_timestamp' => now(),
                    'approval_requested_at' => now()
                ])
            ]);

            DB::commit();
            
            Log::info('Payment paid - pending admin approval', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'user_id' => $transaction->wallet->user_id,
                'user_type' => $transaction->wallet->user_type
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process payment paid', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
            throw $e;
        }
    }

    /**
     * Handle payment.failed event
     */
    private function handlePaymentFailed($payload)
    {
        $paymentIntentId = $payload['data']['id'] ?? null;
        
        if (!$paymentIntentId) {
            Log::warning('Payment intent ID not found in failed payment payload', ['payload' => $payload]);
            return;
        }

        // Find transaction by payment intent ID
        $transaction = WalletTransaction::where('paymongo_payment_intent_id', $paymentIntentId)->first();
        
        if (!$transaction) {
            Log::warning('Transaction not found for failed payment intent ID: ' . $paymentIntentId);
            return;
        }

        // Update transaction status
        $transaction->update(['status' => 'failed']);
        
        Log::info('Payment failed processed', [
            'transaction_id' => $transaction->id,
            'payment_intent_id' => $paymentIntentId
        ]);
    }

    /**
     * Verify webhook signature
     */
    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookSecret = config('services.paymongo.webhook_secret');
        
        // If webhook secret is not configured, allow the request but log a warning
        // This allows the system to work in development, but should be configured in production
        if (!$webhookSecret) {
            Log::warning('Webhook secret not configured - signature verification skipped. Configure PAYMONGO_WEBHOOK_SECRET in production.');
            return true; // Allow in development, but should be configured in production
        }

        $signature = $request->header('PayMongo-Signature');
        $payload = $request->getContent();
        
        if (!$signature) {
            return false;
        }

        // Verify signature using HMAC-SHA256
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

}