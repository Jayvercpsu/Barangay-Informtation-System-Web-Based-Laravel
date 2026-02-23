<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private bool $testMode;
    private string $gatewayUrl;
    private string $gatewayToken;
    private string $testNumber;

    public function __construct()
    {
        $this->testMode = config('sms.test_mode', true);
        $this->gatewayUrl = config('sms.gateway_url', '');
        $this->gatewayToken = config('sms.gateway_token', '');
        $this->testNumber = config('sms.test_number', '');
    }

    public function send(string $number, string $message, array $logData = []): array
    {
        $cleaned = preg_replace('/\D/', '', $number);
        if (str_starts_with($cleaned, '63')) {
            $cleaned = '0' . substr($cleaned, 2);
        }
        $recipient = $this->testMode ? $this->testNumber : $cleaned;

        try {
            if (!$this->testMode && $this->gatewayUrl) {
                $response = Http::timeout(10)
                    ->withoutVerifying()
                    ->post($this->gatewayUrl, [
                        'apikey'  => $this->gatewayToken,
                        'number'  => $recipient,
                        'message' => $message,
                    ]);

                $responseData = $response->json();
                $status = $response->successful() ? 'sent' : 'failed';

                SmsLog::create([
                    'user_id'          => $logData['user_id'] ?? null,
                    'recipient_number' => $number,
                    'message'          => $message,
                    'status'           => $status,
                    'filter_type'      => $logData['filter_type'] ?? null,
                    'filter_value'     => $logData['filter_value'] ?? null,
                ]);

                return [
                    'success'     => $status === 'sent',
                    'status_code' => $response->status(),
                    'response'    => $responseData,
                    'number'      => $recipient,
                ];
            } else {
                SmsLog::create([
                    'user_id'          => $logData['user_id'] ?? null,
                    'recipient_number' => $number,
                    'message'          => $message,
                    'status'           => 'test',
                    'filter_type'      => $logData['filter_type'] ?? null,
                    'filter_value'     => $logData['filter_value'] ?? null,
                ]);

                return ['success' => true, 'status_code' => 200, 'response' => 'test mode', 'number' => $recipient];
            }
        } catch (\Exception $e) {
            Log::error("SMS Send Error: " . $e->getMessage());
            return ['success' => false, 'status_code' => 0, 'response' => $e->getMessage(), 'number' => $recipient];
        }
    }
}