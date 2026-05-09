<?php

namespace SH\OpenAI\Model\Response;

class EmbeddingResponse
{
    public $object;
    public $data = [];
    public $model;
    public $usage;

    public function __construct($data = [])
    {
        $this->object = $data['object'] ?? null;
        $this->data = array_map(function ($item) {
            return new EmbeddingData($item);
        }, $data['data'] ?? []);
        $this->model = $data['model'] ?? null;
        $this->usage = isset($data['usage']) ? new Usage($data['usage']) : null;
    }
}
