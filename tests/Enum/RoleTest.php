<?php

namespace SH\OpenAI\Tests\Enum;

use PHPUnit\Framework\TestCase;
use SH\OpenAI\Enum\Role;

class RoleTest extends TestCase
{
    public function testConstants()
    {
        $this->assertSame('user', Role::USER);
        $this->assertSame('system', Role::SYSTEM);
        $this->assertSame('assistant', Role::ASSISTANT);
        $this->assertSame('function', Role::FUNCTION);
        $this->assertSame('tool', Role::TOOL);
    }
}
