<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private bool $testMode;
    private string $gatewayUrl;
    private string $testNumber;

    public function __construct()
    {
        $this->testMode = config('sms.test_mode', true);
        $this->gatewayUrl = config('sms.gateway_url', '');
        $this->testNumber = config('sms.test_number', '');
    }

    public function send(string $number, string $message, array $logData = []): bool
    {
        $recipient = $this->testMode ? $this->testNumber : $number;

        try {
            if (!$this->testMode && $this->gatewayUrl) {
                $response = Http::timeout(10)->post("{$this->gatewayUrl}/send", [
                    'number' => $recipient,
                    'message' => $message,
                ]);
                $status = $response->successful() ? 'sent' : 'failed';
            } else {
                Log::info("SMS Test Mode - To: {$recipient}, Message: {$message}");
                $status = 'test';
            }

            SmsLog::create([
                'user_id' => $logData['user_id'] ?? null,
                'recipient_number' => $number,
                'message' => $message,
                'status' => $status,
                'filter_type' => $logData['filter_type'] ?? null,
                'filter_value' => $logData['filter_value'] ?? null,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("SMS Send Error: " . $e->getMessage());
            return false;
        }
    }
}