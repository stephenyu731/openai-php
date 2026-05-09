<?php

namespace SH\OpenAI\Tests;

use PHPUnit\Framework\TestCase;
use SH\OpenAI\Exception;

class ExceptionTest extends TestCase
{
    public function testExceptionIsThrowable()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('test error');
        throw new Exception('test error');
    }

    public function testHttpStatusCode()
    {
        $e = new Exception('Not Found', 404);

        $this->assertSame(404, $e->getHttpStatusCode());
        $this->assertSame('Not Found', $e->getMessage());
        $this->assertSame(0, $e->getCode());
    }

    public function testWithCodeAndPrevious()
    {
        $previous = new \Exception('inner');
        $e = new Exception('outer', 500, 42, $previous);

        $this->assertSame(500, $e->getHttpStatusCode());
        $this->assertSame('outer', $e->getMessage());
        $this->assertSame(42, $e->getCode());
        $this->assertSame($previous, $e->getPrevious());
    }

    public function testDefaultValues()
    {
        $e = new Exception();

        $this->assertSame(0, $e->getHttpStatusCode());
        $this->assertSame('', $e->getMessage());
        $this->assertSame(0, $e->getCode());
        $this->assertNull($e->getPrevious());
    }
}
