<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Support/ThinkificBootstrap.php';

\PaymentEngine\Thinkific\Support\ThinkificBootstrap::registerAutoload();

use PaymentEngine\Thinkific\Support\CallbackUrl;
use PaymentEngine\Thinkific\Support\ChargePayloadBuilder;
use PaymentEngine\Thinkific\Support\EnrollmentReference;
use PaymentEngine\Thinkific\Support\PaymentIdentifier;
use PaymentEngine\Thinkific\Support\PaymentStatusMapper;
use PaymentEngine\Thinkific\Support\TransactionVerifier;

$tests = [];

$assertSame = static function (mixed $expected, mixed $actual, string $message): void {
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
    }
};

$assertTrue = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$tests['callback_and_result_urls'] = static function () use ($assertSame): void {
    putenv('THINKIFIC_URL=https://academy.example.com');
    $assertSame('https://academy.example.com/plugin/callback.php', CallbackUrl::forPlugin(), 'Unexpected Thinkific callback URL.');
    $assertSame('https://academy.example.com?payment_status=success&course_id=10', CallbackUrl::resultUrl(10, 'success'), 'Unexpected Thinkific result URL.');
};

$tests['payment_identifier_and_reference'] = static function () use ($assertTrue, $assertSame): void {
    $identifier = PaymentIdentifier::build(25, 100);
    $assertTrue(str_starts_with($identifier, 'THINK-25-100-'), 'Unexpected Thinkific payment identifier format.');
    $assertSame(100, PaymentIdentifier::extractCourseId('THINK-25-100-1718012345'), 'Unexpected Thinkific course extraction.');
    $assertSame('ENR-25-100', EnrollmentReference::build(25, 100), 'Unexpected Thinkific enrollment reference.');
};

$tests['charge_payload_builder'] = static function () use ($assertSame): void {
    $payload = ChargePayloadBuilder::fromRequest([
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'john@example.com',
        'phoneNumber' => '08012345678',
        'currency' => 'NGN',
        'amount' => '15000',
        'courseName' => 'Spring Boot Masterclass',
    ], 'THINK-1-10-123456', 'https://academy.example.com/plugin/callback.php');

    $assertSame('Spring Boot Masterclass', $payload['narration'], 'Unexpected Thinkific narration.');
    $assertSame(1500000, $payload['amount'], 'Unexpected Thinkific amount conversion.');

    $defaultPayload = ChargePayloadBuilder::fromRequest([
        'amount' => '10000',
    ], 'THINK-1-10-123456', 'https://academy.example.com/plugin/callback.php');

    $assertSame('Thinkific Course Purchase', $defaultPayload['narration'], 'Unexpected Thinkific default narration.');
};

$tests['payment_status_mapping'] = static function () use ($assertSame): void {
    $assertSame(PaymentStatusMapper::STATUS_SUCCESS, PaymentStatusMapper::mapQueryResponse(['status' => '00']), 'Unexpected Thinkific success mapping.');
    $assertSame(PaymentStatusMapper::STATUS_SUCCESS, PaymentStatusMapper::mapQueryResponse(['data' => ['paymentState' => 'APPROVED']]), 'Unexpected Thinkific approved mapping.');
    $assertSame(PaymentStatusMapper::STATUS_PENDING, PaymentStatusMapper::mapQueryResponse(['status' => '01']), 'Unexpected Thinkific pending mapping.');
    $assertSame(PaymentStatusMapper::STATUS_FAILED, PaymentStatusMapper::mapQueryResponse(['status' => '99']), 'Unexpected Thinkific failed mapping.');
};

$tests['transaction_verifier_with_injected_query'] = static function () use ($assertSame): void {
    $response = TransactionVerifier::verify('https://api-checkout-qa.systemspecsng.com', 'secret', 'THINK-1-10-123', static fn (string $paymentIdentifier): array => [
        'status' => '00',
        'data' => [
            'paymentIdentifier' => $paymentIdentifier,
            'paymentState' => 'APPROVED',
        ],
    ]);

    $assertSame('THINK-1-10-123', $response['data']['paymentIdentifier'], 'Unexpected injected Thinkific verifier response.');
};

$executed = 0;

foreach ($tests as $name => $test) {
    $test();
    $executed++;
    echo "[PASS] {$name}\n";
}

echo "\nAll {$executed} Thinkific tests passed.\n";
