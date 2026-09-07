<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\AmountNormalizer;

final class AmountNormalizerTest extends TestCase
{
    public function testToKobo(): void
    {
        $this->assertSame(
            1500000,
            AmountNormalizer::toKobo('15000')
        );
    }

    public function testToKoboWithDecimalAmount(): void
    {
        $this->assertSame(
            150050,
            AmountNormalizer::toKobo('1500.50')
        );
    }

    public function testFromKobo(): void
    {
        $this->assertSame(
            15000.00,
            AmountNormalizer::fromKobo(1500000)
        );
    }

    public function testFromKoboWithDecimalAmount(): void
    {
        $this->assertSame(
            1500.50,
            AmountNormalizer::fromKobo(150050)
        );
    }
}