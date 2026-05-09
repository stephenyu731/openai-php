<?php

namespace SH\OpenAI\Model\Response;

class ImageResponse
{
    public $created;
    public $data = [];

    public function __construct($data = [])
    {
        $this->created = $data['created'] ?? null;
        $this->data = array_map(function ($item) {
            return new ImageData($item);
        }, $data['data'] ?? []);
    }
}
