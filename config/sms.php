<?php

return [
    'test_mode' => env('SMS_TEST_MODE', true),
    'gateway_url' => env('SMS_GATEWAY_URL', ''),
    'gateway_token' => env('SMS_GATEWAY_TOKEN', ''),
    'test_number' => env('SMS_TEST_NUMBER', ''),
];