<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\ThinkificBootstrap;

final class ThinkificBootstrapTest extends TestCase
{
    public function testRegisterAutoloadMethodExists(): void
    {
        $this->assertTrue(
            method_exists(
                ThinkificBootstrap::class,
                'registerAutoload'
            )
        );
    }
}