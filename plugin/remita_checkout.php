<?php

declare(strict_types=1);

use PaymentEngine\Thinkific\Support\CallbackUrl;
use PaymentEngine\Thinkific\Support\ThinkificBootstrap;

require_once dirname(__DIR__) . '/src/Support/ThinkificBootstrap.php';

ThinkificBootstrap::registerAutoload();

return [
    'name' => 'Remita Checkout',

    'version' => '1.0.0',

    'configuration' => [

        'base_url' => [
            'label' => 'API Base URL',
            'type' => 'text',
            'default' => 'https://api-checkout-qa.systemspecsng.com',
        ],

        'secret_key' => [
            'label' => 'Secret Key',
            'type' => 'password',
        ],

        'callback_url' => [
            'label' => 'Callback URL',
            'type' => 'text',
            'default' => CallbackUrl::forPlugin(),
        ],
    ],
];
