<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Support;

final class PaymentIdentifier
{
    public static function build(
        int $studentId,
        int $courseId
    ): string {

        return sprintf(
            'THINK-%d-%d-%d',
            $studentId,
            $courseId,
            time()
        );
    }

    public static function extractCourseId(
        string $paymentIdentifier
    ): ?int {

        $parts = explode('-', $paymentIdentifier);

        return isset($parts[2])
            ? (int) $parts[2]
            : null;
    }
}