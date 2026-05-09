<?php

namespace SH\OpenAI\Model\Response;

class EmbeddingData
{
    public $object;
    public $embedding;
    public $index;

    public function __construct($data = [])
    {
        $this->object = $data['object'] ?? null;
        $this->embedding = $data['embedding'] ?? null;
        $this->index = $data['index'] ?? null;
    }
}
