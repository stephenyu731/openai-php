<?php

namespace SH\OpenAI\Model\Response;

class Usage
{
    public $prompt_tokens;
    public $completion_tokens;
    public $total_tokens;
    public $completionTokensDetails;
    public $promptTokensDetails;

    public function __construct($data = [])
    {
        $this->prompt_tokens = $data['prompt_tokens'] ?? null;
        $this->completion_tokens = $data['completion_tokens'] ?? null;
        $this->total_tokens = $data['total_tokens'] ?? null;
        $this->completionTokensDetails = $data['completion_tokens_details'] ?? null;
        $this->promptTokensDetails = $data['prompt_tokens_details'] ?? null;
    }
}
