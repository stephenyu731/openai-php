<?php

namespace SH\OpenAI\Model\Response;

class Delta
{
    public $role;
    public $content;
    public $refusal;
    public $toolCalls = [];

    public function __construct($data = [])
    {
        $this->role = $data['role'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->refusal = $data['refusal'] ?? null;
        $this->toolCalls = array_map(function ($tc) {
            return new ToolCall($tc);
        }, $data['tool_calls'] ?? []);
    }
}
