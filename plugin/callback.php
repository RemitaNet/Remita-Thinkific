<?php

declare(strict_types=1);

use PaymentEngine\Thinkific\Support\CallbackUrl;
use PaymentEngine\Thinkific\Support\PaymentIdentifier;
use PaymentEngine\Thinkific\Support\PaymentStatusMapper;
use PaymentEngine\Thinkific\Support\ThinkificBootstrap;
use PaymentEngine\Thinkific\Support\TransactionVerifier;

require_once dirname(__DIR__) . '/src/Support/ThinkificBootstrap.php';

ThinkificBootstrap::registerAutoload();

$paymentIdentifier = trim((string) ($_GET['paymentIdentifier'] ?? ''));

if ($paymentIdentifier === '') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing payment identifier']);
    exit;
}

$response = TransactionVerifier::verify(
    (string) getenv('PAYMENT_ENGINE_BASE_URL'),
    (string) getenv('PAYMENT_ENGINE_SECRET_KEY'),
    $paymentIdentifier
);

$status = PaymentStatusMapper::mapQueryResponse($response);
$courseId = PaymentIdentifier::extractCourseId($paymentIdentifier) ?? 0;
$payload = [
    'paymentIdentifier' => $paymentIdentifier,
    'status' => $status,
    'transaction' => $response['data'] ?? [],
];

$accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');

if (str_contains($accept, 'application/json')) {
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

$returnUrl = CallbackUrl::resultUrl($courseId, $status);

header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html lang="en"><body><h1>Payment ' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</h1><p>Reference: ' . htmlspecialchars($paymentIdentifier, ENT_QUOTES, 'UTF-8') . '</p><p><a href="' . htmlspecialchars($returnUrl, ENT_QUOTES, 'UTF-8') . '">Back to Thinkific</a></p></body></html>';
