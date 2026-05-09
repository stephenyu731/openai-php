<?php

namespace SH\OpenAI\Model\Response;

class ModerationResponse
{
    public $id;
    public $model;
    public $results = [];

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->results = array_map(function ($item) {
            return new ModerationResult($item);
        }, $data['results'] ?? []);
    }
}
