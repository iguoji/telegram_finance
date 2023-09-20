<?php

return [
    'bot' => [
        'api_token' => env('TELEGRAM_API_TOKEN', '6385946365:AAH-_mTWIIB4Gt9h529UKUhrntXt4V70Hmk'),

        'username' => env('TELEGRAM_BOT_USERNAME', 'zidongjizhang_bot'),

        'api_url' => env('TELEGRAM_API_URL'),
    ],

    'admins' => env('TELEGRAM_ADMINS', '')

];
