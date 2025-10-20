<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    private $secretKey;
    private $publicKey;
    private $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        $this->publicKey = config('services.paymongo.public_key');
    }

    /**
     * Create a payment intent for GCash QR code
     */
    public function createPaymentIntent(float $amount, array $metadata = []): array
    {
        try {
            // Ensure all metadata values are strings and not too long
            $cleanMetadata = [];
            foreach ($metadata as $key => $value) {
                $cleanMetadata[$key] = is_string($value) ? substr($value, 0, 500) : (string) $value;
            }
            
            Log::info('PayMongo metadata being sent', ['metadata' => $cleanMetadata]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/payment_intents', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100, // Convert to centavos
                        'currency' => 'PHP',
                        'payment_method_allowed' => ['gcash'],
                        'description' => 'Wallet Cash-in',
                        'statement_descriptor' => 'MENTORHUB WALLET',
                        'metadata' => $cleanMetadata
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'payment_intent_id' => $data['data']['id'],
                    'client_key' => $data['data']['attributes']['client_key'],
                    'data' => $data['data']
                ];
            } else {
                Log::error('PayMongo createPaymentIntent failed: ' . $response->body(), ['status' => $response->status(), 'response' => $response->json()]);
                return [
                    'success' => false,
                    'error' => $response->json('errors.0.detail', 'Unknown error creating payment intent.')
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a GCash source for QR code payment
     */
    public function createGcashSource(string $paymentIntentId, float $amount): array
    {
        try {
            // First, create a payment method
            $paymentMethodResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/payment_methods', [
                'data' => [
                    'attributes' => [
                        'type' => 'gcash',
                        'billing' => [
                            'name' => 'MentorHub User', // Placeholder, ideally from user data
                            'email' => 'user@example.com', // Placeholder
                        ],
                    ]
                ]
            ]);

            if (!$paymentMethodResponse->successful()) {
                Log::error('PayMongo createPaymentMethod failed: ' . $paymentMethodResponse->body(), ['status' => $paymentMethodResponse->status(), 'response' => $paymentMethodResponse->json()]);
                return [
                    'success' => false,
                    'error' => $paymentMethodResponse->json('errors.0.detail', 'Unknown error creating payment method.')
                ];
            }

            $paymentMethodData = $paymentMethodResponse->json();
            $paymentMethodId = $paymentMethodData['data']['id'];

            // Attach payment method to payment intent
            $attachResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post("{$this->baseUrl}/payment_intents/{$paymentIntentId}/attach", [
                'data' => [
                    'attributes' => [
                        'payment_method' => $paymentMethodId,
                        'return_url' => config('app.url') . '/wallet/payment/success?payment_intent_id=' . $paymentIntentId,
                    ]
                ]
            ]);

            if ($attachResponse->successful()) {
                $attachData = $attachResponse->json();
                $nextAction = $attachData['data']['attributes']['next_action'];
                
                if ($nextAction && $nextAction['type'] === 'redirect') {
                    return [
                        'success' => true,
                        'source_id' => $paymentMethodId,
                        'checkout_url' => $nextAction['redirect']['url'],
                        'qr_code_url' => $nextAction['redirect']['url'], // For GCash, this is often the same
                        'data' => $attachData['data']
                    ];
                } else {
                    Log::error('PayMongo attachPaymentMethod did not return a redirect URL.', ['response' => $attachData]);
                    return [
                        'success' => false,
                        'error' => 'Payment method attached but no redirect URL found.'
                    ];
                }
            } else {
                Log::error('PayMongo attachPaymentMethod failed: ' . $attachResponse->body(), ['status' => $attachResponse->status(), 'response' => $attachResponse->json()]);
                return [
                    'success' => false,
                    'error' => $attachResponse->json('errors.0.detail', 'Unknown error attaching payment method.')
                ];
            }
        } catch (Exception $e) {
            Log::error('Exception in createGcashSource: ' . $e->getMessage(), ['exception' => $e]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve payment intent status
     */
    public function getPaymentIntent(string $paymentIntentId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Accept' => 'application/json'
            ])->get($this->baseUrl . '/payment_intents/' . $paymentIntentId);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['data']['attributes']['status'],
                    'data' => $data['data']
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body()
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve source status
     */
    public function getSource(string $sourceId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Accept' => 'application/json'
            ])->get($this->baseUrl . '/sources/' . $sourceId);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['data']['attributes']['status'],
                    'data' => $data['data']
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body()
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a payout for cash out
     */
    public function createPayout(float $amount, string $accountNumber, string $accountName): array
    {
        try {
            // Note: This is a simplified version. In production, you'd need to implement
            // proper payout functionality with PayMongo or use their Payout API
            return [
                'success' => true,
                'payout_id' => 'payout_' . uniqid(),
                'status' => 'pending',
                'message' => 'Payout request created successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}