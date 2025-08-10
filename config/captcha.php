<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'enabled' => env('CAPTCHA_ENABLE', true),
    'options' => [
        'timeout' => 30,
    ],
    'curl_options' => [
        CURLOPT_SSL_VERIFYPEER => env('CAPTCHA_DISABLE_SSL', false) ? false : true,
        CURLOPT_SSL_VERIFYHOST => env('CAPTCHA_DISABLE_SSL', false) ? 0 : 2,
    ],
];
