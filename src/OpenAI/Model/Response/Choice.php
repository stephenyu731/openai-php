<?php

namespace SH\OpenAI\Model\Response;

class Choice {
    public $logprobs;
    public $finish_reason;
    public $index;
    /**
     * @var Message
     */
    public $message;

    public function __construct($data = []) {
        $this->logprobs = $data['logprobs'] ?? null;
        $this->finish_reason = $data['finish_reason'] ?? null;
        $this->index = $data['index'] ?? null;
        $this->message = new Message($data['message'] ?? []);
        
    }
}