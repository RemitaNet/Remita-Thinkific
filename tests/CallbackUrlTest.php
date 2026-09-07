<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\CallbackUrl;

final class CallbackUrlTest extends TestCase
{
    public function testGatewayUrl(): void
    {
        putenv('THINKIFIC_URL=https://academy.example.com');

        $this->assertSame(
            'https://academy.example.com/paymentengine/callback.php',
            CallbackUrl::forGateway()
        );
    }
}