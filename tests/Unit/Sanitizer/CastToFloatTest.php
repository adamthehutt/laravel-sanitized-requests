<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::castToFloat */
class CastToFloatTest extends TestCase
{
    /** @test */
    public function it_casts_a_numeric_string()
    {
        $input = ['foo' => '123.45'];

        $subject = new Sanitizer($input);
        $subject->castToFloat("foo");

        $this->assertSame(["foo" => 123.45], $subject->getSanitized());
    }
}
