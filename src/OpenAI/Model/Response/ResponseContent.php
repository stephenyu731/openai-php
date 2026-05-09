<?php

namespace SH\OpenAI\Model\Response;

class ResponseContent
{
    public $type;
    public $text;
    public $annotations;

    public function __construct($data = [])
    {
        $this->type = $data['type'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->annotations = $data['annotations'] ?? null;
    }
}
