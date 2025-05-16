<?php

namespace SH\OpenAI\Model\Response;

class Message {
    public $role;
    public $content;
    public $refusal;

    /**
     * 
     * @var ToolCall array
     */
    public $tool_calls;

    public function __construct($data = []) {
        $this->role = $data['role'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->refusal = $data['refusal'] ?? null;
        $this->tool_calls = array_map(function($tool_call) {
            return new ToolCall($tool_call);
        }, $data['tool_calls'] ?? []);
    }
}