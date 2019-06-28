<?php
declare(strict_types=1);

namespace AdamTheHutt\SanitizedRequests\Tests\Unit\Sanitizer;

use AdamTheHutt\SanitizedRequests\Sanitizer;
use Orchestra\Testbench\TestCase;

/** @covers \AdamTheHutt\SanitizedRequests\Sanitizer::forgetIfEmpty */
class ForgetIfEmptyTest extends TestCase
{
    /** @test */
    public function it_forgets_an_empty_string()
    {
        $input = ['foo' => ''];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("foo");

        $this->assertArrayNotHasKey("foo", $subject->input);
    }

    /** @test */
    public function it_forgets_an_empty_nested_string()
    {
        $input = ['foo' => ['bar' => ['baz' => '']]];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("foo.bar.baz");

        $this->assertSame(["foo" => ["bar" => []]], $subject->input);
    }

    /** @test */
    public function it_forgets_an_empty_nested_string_with_wildcard()
    {
        $input = [
            'foo' => [
                'bar' => [
                    'baz' => ''
                ],
                'gorilla' => [
                    'baz' => ''
                ],
                2 => [
                    'baz' => ''
                ]
            ]
        ];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("foo.*.baz");

        $this->assertSame(["foo" => ["bar" => [], 'gorilla' => [], 2 => []]], $subject->input);
    }

    /** @test */
    public function it_forgets_an_empty_array()
    {
        $input = [
            'foo' => [
                'bar' => [
                    'baz' => ''
                ],
                'gorilla' => [
                    'baz' => ''
                ],
                2 => [
                    'baz' => ''
                ]
            ]
        ];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("foo.bar");

        $this->assertSame(["foo" => ['gorilla' => ['baz' => ''], 2 => ['baz' => '']]], $subject->input);
    }

    /** @test */
    public function it_leaves_a_non_empty_string()
    {
        $input = ['abc' => '123'];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("abc");

        $this->assertSame(['abc' => '123'], $subject->input);
    }

    /** @test */
    public function it_leaves_a_non_empty_array()
    {
        $input = ['abc' => ['def' => '123']];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("abc");

        $this->assertSame(['abc' => ['def' => '123']], $subject->input);
    }

    /** @test */
    public function it_forgets_an_empty_array_with_trailing_wildcard()
    {
        $input = [
            'abc' => [
                'def' => [
                    'ghi' => '',
                    'jkl' => '',
                    'mno' => ''
                ],
                'pqr' => [
                    'stu' => 'blah',
                    'vwx' => 'blah',
                    'ynz' => 'blah'
                ]
            ]
        ];

        $subject = new Sanitizer($input);
        $subject->forgetIfEmpty("abc.*");

        $this->assertSame(['abc' => ['pqr' => ['stu' => 'blah', 'vwx' => 'blah', 'ynz' => 'blah']]], $subject->input);
    }
}
