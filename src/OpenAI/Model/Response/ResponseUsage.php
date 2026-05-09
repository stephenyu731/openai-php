<?php

namespace SH\OpenAI\Model\Response;

class ResponseUsage
{
    public $inputTokens;
    public $outputTokens;
    public $outputTokensDetails;
    public $totalTokens;

    public function __construct($data = [])
    {
        $this->inputTokens = $data['input_tokens'] ?? null;
        $this->outputTokens = $data['output_tokens'] ?? null;
        $this->outputTokensDetails = $data['output_tokens_details'] ?? null;
        $this->totalTokens = $data['total_tokens'] ?? null;
    }
}
