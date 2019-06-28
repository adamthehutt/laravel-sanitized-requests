<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::castToBool */
class CastToBoolTest extends TestCase
{
    /** @test */
    public function it_casts_a_string_to_true()
    {
        $input = ['foo' => 'true'];

        $subject = new Sanitizer($input);
        $subject->castToBool("foo");

        $this->assertSame(["foo" => true], $subject->getSanitized());
    }

    /** @test */
    public function it_casts_a_numeric_string_to_true()
    {
        $input = ['foo' => '1'];

        $subject = new Sanitizer($input);
        $subject->castToBool("foo");

        $this->assertSame(["foo" => true], $subject->getSanitized());
    }

    /** @test */
    public function it_casts_a_numeric_string_to_false()
    {
        $input = ['foo' => '0'];

        $subject = new Sanitizer($input);
        $subject->castToBool("foo");

        $this->assertSame(["foo" => false], $subject->getSanitized());
    }

    /** @test */
    public function it_casts_an_empty_string_to_false()
    {
        $input = ['foo' => ''];

        $subject = new Sanitizer($input);
        $subject->castToBool("foo");

        $this->assertSame(["foo" => false], $subject->getSanitized());
    }
}
