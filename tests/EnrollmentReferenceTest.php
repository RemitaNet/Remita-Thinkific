<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\EnrollmentReference;

final class EnrollmentReferenceTest extends TestCase
{
    public function testBuildReference(): void
    {
        $this->assertSame(
            'ENR-25-100',
            EnrollmentReference::build(
                25,
                100
            )
        );
    }

    public function testExtractStudentId(): void
    {
        $this->assertSame(
            25,
            EnrollmentReference::extractStudentId(
                'ENR-25-100'
            )
        );
    }

    public function testExtractCourseId(): void
    {
        $this->assertSame(
            100,
            EnrollmentReference::extractCourseId(
                'ENR-25-100'
            )
        );
    }
}