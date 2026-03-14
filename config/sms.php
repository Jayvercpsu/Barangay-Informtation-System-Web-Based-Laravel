<?php

return [
    'provider' => env('SMS_PROVIDER', 'philsms'),
    'test_mode' => env('SMS_TEST_MODE', true),
    'gateway_url' => env('SMS_GATEWAY_URL', 'https://dashboard.philsms.com/api/v3/sms/send'),
    'gateway_token' => env('SMS_GATEWAY_TOKEN', ''),
    'test_number' => env('SMS_TEST_NUMBER', ''),
    'sender_id' => env('SMS_SENDER_ID', 'PhilSMS'),
    'message_type' => env('SMS_MESSAGE_TYPE', 'plain'),
    'verify_ssl' => env('SMS_VERIFY_SSL', true),
    'ca_bundle' => env('SMS_CA_BUNDLE', ''),
];
