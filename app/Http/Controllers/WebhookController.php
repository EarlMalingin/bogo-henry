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

        // For testing, temporarily skip signature verification
        // TODO: Re-enable signature verification in production
        /*
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid webhook signature received');
            return response()->json(['error' => 'Invalid signature'], 401);
        }
        */

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
            // Add funds to wallet
            $transaction->wallet->addFunds($transaction->amount, 'cash_in', [
                'payment_intent_id' => $paymentIntentId,
                'webhook_verified' => true,
                'webhook_timestamp' => now()
            ]);

            // Update transaction status
            $transaction->update([
                'status' => 'completed',
                'reference_number' => $paymentIntentId
            ]);

            DB::commit();
            
            Log::info('Payment paid processed successfully', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount
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
        
        if (!$webhookSecret) {
            Log::warning('Webhook secret not configured');
            return false;
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

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        Log::info('Test webhook called', ['payload' => $request->all()]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook endpoint is working',
            'timestamp' => now()
        ]);
    }
}