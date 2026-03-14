<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $provider;
    private bool $testMode;
    private bool $verifySsl;
    private string $gatewayUrl;
    private string $gatewayToken;
    private string $testNumber;
    private string $senderId;
    private string $messageType;
    private string $caBundle;

    public function __construct()
    {
        $this->provider = strtolower((string) config('sms.provider', 'philsms'));
        $this->testMode = config('sms.test_mode', true);
        $this->verifySsl = (bool) config('sms.verify_ssl', true);
        $this->gatewayUrl = (string) config('sms.gateway_url', '');
        $this->gatewayToken = (string) config('sms.gateway_token', '');
        $this->testNumber = (string) config('sms.test_number', '');
        $this->senderId = (string) config('sms.sender_id', 'PhilSMS');
        $this->messageType = (string) config('sms.message_type', 'plain');
        $this->caBundle = (string) config('sms.ca_bundle', '');
    }

    public function send(string $number, string $message, array $logData = []): array
    {
        $normalized = $this->normalizePhilippineNumber($number);
        $testRecipient = $this->normalizePhilippineNumber($this->testNumber);

        if ($normalized === null) {
            SmsLog::create([
                'user_id' => $logData['user_id'] ?? null,
                'recipient_number' => $number,
                'message' => $message,
                'status' => 'failed',
                'filter_type' => $logData['filter_type'] ?? null,
                'filter_value' => $logData['filter_value'] ?? null,
            ]);

            return [
                'success' => false,
                'status_code' => 0,
                'response' => 'Invalid recipient number format.',
                'number' => $number,
                'normalized_number' => null,
            ];
        }

        $recipient = $this->testMode && $testRecipient !== null ? $testRecipient : $normalized;

        if (!$this->testMode && (empty($this->gatewayUrl) || empty($this->gatewayToken))) {
            return [
                'success' => false,
                'status_code' => 0,
                'response' => 'SMS gateway is not configured.',
                'number' => $number,
                'normalized_number' => $normalized,
            ];
        }

        try {
            if (!$this->testMode) {
                $http = Http::acceptJson()
                    ->timeout(20);

                if ($this->verifySsl === false) {
                    $http = $http->withOptions(['verify' => false]);
                } elseif ($this->caBundle !== '') {
                    $http = $http->withOptions(['verify' => $this->caBundle]);
                }

                if ($this->provider === 'semaphore') {
                    $payload = [
                        'apikey' => $this->gatewayToken,
                        'number' => $recipient,
                        'message' => $message,
                    ];

                    if ($this->senderId !== '') {
                        $payload['sendername'] = $this->senderId;
                    }

                    $response = $http
                        ->asForm()
                        ->post($this->gatewayUrl, $payload);
                } else {
                    $payload = [
                        'recipient' => $recipient,
                        'sender_id' => $this->senderId !== '' ? $this->senderId : 'PhilSMS',
                        'type' => $this->resolveMessageType($message),
                        'message' => $message,
                    ];

                    $response = $http
                        ->withToken($this->gatewayToken)
                        ->asJson()
                        ->post($this->gatewayUrl, $payload);
                }

                $responseData = $response->json();
                $rawBody = $response->body();

                $success = $this->isSuccessfulProviderResponse(
                    $response->status(),
                    $responseData,
                    $rawBody
                );

                $status = $success ? 'sent' : 'failed';

                SmsLog::create([
                    'user_id'          => $logData['user_id'] ?? null,
                    'recipient_number' => $recipient,
                    'message'          => $message,
                    'status'           => $status,
                    'filter_type'      => $logData['filter_type'] ?? null,
                    'filter_value'     => $logData['filter_value'] ?? null,
                ]);

                return [
                    'success'     => $success,
                    'status_code' => $response->status(),
                    'response'    => $responseData ?? $rawBody,
                    'number'      => $recipient,
                    'normalized_number' => $normalized,
                ];
            } else {
                SmsLog::create([
                    'user_id'          => $logData['user_id'] ?? null,
                    'recipient_number' => $recipient,
                    'message'          => $message,
                    'status'           => 'test',
                    'filter_type'      => $logData['filter_type'] ?? null,
                    'filter_value'     => $logData['filter_value'] ?? null,
                ]);

                return [
                    'success' => true,
                    'status_code' => 200,
                    'response' => 'test mode',
                    'number' => $recipient,
                    'normalized_number' => $normalized,
                ];
            }
        } catch (\Exception $e) {
            Log::error("SMS Send Error: " . $e->getMessage());
            return [
                'success' => false,
                'status_code' => 0,
                'response' => $e->getMessage(),
                'number' => $recipient,
                'normalized_number' => $normalized,
            ];
        }
    }

    private function normalizePhilippineNumber(?string $number): ?string
    {
        if ($number === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $number);
        if (empty($digits)) {
            return null;
        }

        if (str_starts_with($digits, '09') && strlen($digits) === 11) {
            return '63' . substr($digits, 1);
        }

        if (str_starts_with($digits, '9') && strlen($digits) === 10) {
            return '63' . $digits;
        }

        if (str_starts_with($digits, '639') && strlen($digits) === 12) {
            return $digits;
        }

        return null;
    }

    private function resolveMessageType(string $message): string
    {
        $configured = strtolower(trim($this->messageType));

        if (preg_match('/[^\x20-\x7E]/', $message) === 1) {
            return 'unicode';
        }

        return in_array($configured, ['plain', 'unicode'], true) ? $configured : 'plain';
    }

    /**
     * @param mixed $responseData
     */
    private function isSuccessfulProviderResponse(int $statusCode, $responseData, string $rawBody): bool
    {
        if ($this->provider === 'semaphore') {
            return $this->isSuccessfulSemaphoreResponse($statusCode, $responseData, $rawBody);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            return false;
        }

        if (is_array($responseData)) {
            if (isset($responseData['success']) && is_bool($responseData['success'])) {
                return $responseData['success'];
            }

            if (isset($responseData['error']) && !empty($responseData['error'])) {
                return false;
            }

            if (isset($responseData['status'])) {
                $statusText = strtolower((string) $responseData['status']);
                if (str_contains($statusText, 'fail') || str_contains($statusText, 'error') || str_contains($statusText, 'reject')) {
                    return false;
                }
                if (str_contains($statusText, 'success') || str_contains($statusText, 'ok') || str_contains($statusText, 'accept')) {
                    return true;
                }
            }

            if (array_is_list($responseData) && isset($responseData[0]['status'])) {
                $statusText = strtolower((string) $responseData[0]['status']);
                return !str_contains($statusText, 'fail') && !str_contains($statusText, 'error');
            }
        }

        $body = strtolower($rawBody);
        if ($body !== '') {
            if (str_contains($body, 'fail') || str_contains($body, 'error') || str_contains($body, 'invalid') || str_contains($body, 'reject')) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $responseData
     */
    private function isSuccessfulSemaphoreResponse(int $statusCode, $responseData, string $rawBody): bool
    {
        if ($statusCode < 200 || $statusCode >= 300) {
            return false;
        }

        if (is_array($responseData)) {
            if (isset($responseData['error']) && !empty($responseData['error'])) {
                return false;
            }

            if (isset($responseData['status']) && $this->statusIndicatesFailure((string) $responseData['status'])) {
                return false;
            }

            if (array_is_list($responseData)) {
                if ($responseData === []) {
                    return false;
                }

                foreach ($responseData as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    if (isset($item['error']) && !empty($item['error'])) {
                        return false;
                    }

                    if (isset($item['status']) && $this->statusIndicatesFailure((string) $item['status'])) {
                        return false;
                    }
                }

                return true;
            }
        }

        $body = strtolower($rawBody);
        if ($body !== '') {
            if (str_contains($body, 'fail') || str_contains($body, 'error') || str_contains($body, 'invalid') || str_contains($body, 'reject')) {
                return false;
            }
        }

        return true;
    }

    private function statusIndicatesFailure(string $status): bool
    {
        $status = strtolower($status);

        return str_contains($status, 'fail')
            || str_contains($status, 'error')
            || str_contains($status, 'reject')
            || str_contains($status, 'invalid');
    }
}
