<?php

namespace SH\OpenAI\Model\Response;

class ToolCall {
    public $index;
    public $id;
    public $type;

    /**
     * 
     * @var ToolCallFunction
     */
    public $function;

    public function __construct($data = []) {
        $this->index = $data['index'] ?? null;
        $this->id = $data['id'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->function = new ToolCallFunction($data['function'] ?? []);
    }
}