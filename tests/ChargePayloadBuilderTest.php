<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\ChargePayloadBuilder;

final class ChargePayloadBuilderTest extends TestCase
{
    public function testBuildPayload(): void
    {
        $payload = ChargePayloadBuilder::fromRequest(
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john@example.com',
                'phoneNumber' => '08012345678',
                'currency' => 'NGN',
                'amount' => '15000',
                'courseName' => 'Spring Boot Masterclass',
            ],
            'THINK-1-10-123456',
            'https://academy.example.com/paymentengine/callback.php'
        );

        $this->assertSame('John', $payload['firstName']);
        $this->assertSame('Doe', $payload['lastName']);
        $this->assertSame('john@example.com', $payload['email']);
        $this->assertSame('08012345678', $payload['phoneNumber']);
        $this->assertSame('THINK-1-10-123456', $payload['paymentIdentifier']);
        $this->assertSame('Spring Boot Masterclass', $payload['narration']);
        $this->assertSame(1500000, $payload['amount']);
    }

    public function testDefaultNarration(): void
    {
        $payload = ChargePayloadBuilder::fromRequest(
            [
                'amount' => '10000'
            ],
            'THINK-1-10-123456',
            'https://academy.example.com/paymentengine/callback.php'
        );

        $this->assertSame(
            'Thinkific Course',
            $payload['narration']
        );
    }
}