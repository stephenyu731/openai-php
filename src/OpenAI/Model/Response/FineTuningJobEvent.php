<?php

namespace SH\OpenAI\Model\Response;

class FineTuningJobEvent
{
    public $id;
    public $object;
    public $createdAt;
    public $level;
    public $message;
    public $data;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->level = $data['level'] ?? null;
        $this->message = $data['message'] ?? null;
        $this->data = $data['data'] ?? null;
    }
}
