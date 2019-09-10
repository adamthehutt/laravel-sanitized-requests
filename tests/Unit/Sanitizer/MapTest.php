<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::map */
class MapTest extends TestCase
{
    /** @test */
    public function it_maps_with_callback()
    {
        $input = ['foo' => 'abc'];

        $subject = new Sanitizer($input);
        $subject->map("foo", function($input) {
            return 'More cowbell!';
        });

        $this->assertSame(["foo" => 'More cowbell!'], $subject->getSanitized());
    }

    /** @test */
    public function it_maps_with_callback2()
    {
        $input = ['foo' => 'abc'];

        $subject = new Sanitizer($input);
        $subject->map("foo", function($input) {
            return strrev($input);
        });

        $this->assertSame(["foo" => 'cba'], $subject->getSanitized());
    }

    /** @test */
    public function it_maps_with_native_function_name()
    {
        $input = ['foo' => 'abc'];

        $subject = new Sanitizer($input);
        $subject->map("foo", 'strrev');

        $this->assertSame(["foo" => 'cba'], $subject->getSanitized());
    }
}
