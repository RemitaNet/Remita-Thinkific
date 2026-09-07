<?php

declare(strict_types=1);

namespace PaymentEngine\Thinkific\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Thinkific\Support\PaymentIdentifier;

final class PaymentIdentifierTest extends TestCase
{
    public function testBuildIdentifier(): void
    {
        $identifier = PaymentIdentifier::build(
            25,
            100
        );

        $this->assertStringStartsWith(
            'THINK-25-100-',
            $identifier
        );
    }

    public function testExtractCourseId(): void
    {
        $this->assertSame(
            100,
            PaymentIdentifier::extractCourseId(
                'THINK-25-100-1718012345'
            )
        );
    }
}