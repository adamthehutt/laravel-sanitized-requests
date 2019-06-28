<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::trimWhitespace */
class TrimWhitespaceTest extends TestCase
{
    /** @test */
    public function it_trims_a_padded_string()
    {
        $input = ['foo' => '     abc            '];

        $subject = new Sanitizer($input);
        $subject->trimWhitespace("foo");

        $this->assertSame(["foo" => 'abc'], $subject->input);
    }
}
