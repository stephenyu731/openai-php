<?php

namespace SH\OpenAI\Model\Response;

class ToolCallFunction {

    /**
     * @var string 
     */
    public $name;

    /**
     * @var string 
     */
    public $arguments;
    
    public function __construct($data = []) {
        $this->name = $data['name'] ?? null;
        $this->arguments = $data['arguments'] ?? null;
    }
}