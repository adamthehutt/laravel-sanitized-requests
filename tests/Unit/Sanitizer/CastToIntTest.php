<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::castToInt */
class CastToIntTest extends TestCase
{
    /** @test */
    public function it_casts_a_numeric_string()
    {
        $input = ['foo' => '123'];

        $subject = new Sanitizer($input);
        $subject->castToInt("foo");

        $this->assertSame(["foo" => 123], $subject->getSanitized());
    }
}
