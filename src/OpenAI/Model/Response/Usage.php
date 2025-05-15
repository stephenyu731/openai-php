<?php

namespace SH\OpenAI\Model\Response;

class Usage {
    public $prompt_tokens;
    public $completion_tokens;
    public $total_tokens;

    public function __construct($data = []) {
        $this->prompt_tokens = $data['prompt_tokens'] ?? null;
        $this->completion_tokens = $data['completion_tokens'] ?? null;
        $this->total_tokens = $data['total_tokens'] ?? null;
    }
}