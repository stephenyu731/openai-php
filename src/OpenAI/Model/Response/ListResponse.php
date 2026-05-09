<?php

namespace SH\OpenAI\Model\Response;

class ListResponse
{
    public $object;
    public $data = [];
    public $firstId;
    public $lastId;
    public $hasMore;

    public function __construct($data = [])
    {
        $this->object = $data['object'] ?? null;
        $this->data = $data['data'] ?? [];
        $this->firstId = $data['first_id'] ?? null;
        $this->lastId = $data['last_id'] ?? null;
        $this->hasMore = $data['has_more'] ?? null;
    }
}
