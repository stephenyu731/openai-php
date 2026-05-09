<?php

namespace SH\OpenAI\Model\Response;

class Choice
{
    public $logprobs;
    public $finish_reason;
    public $index;
    /**
     * @var Message
     */
    public $message;
    /**
     * @var Delta|null
     */
    public $delta;

    public function __construct($data = [])
    {
        $this->logprobs = $data['logprobs'] ?? null;
        $this->finish_reason = $data['finish_reason'] ?? null;
        $this->index = $data['index'] ?? null;
        $this->message = isset($data['message']) ? new Message($data['message']) : null;
        $this->delta = isset($data['delta']) ? new Delta($data['delta']) : null;
    }
}
