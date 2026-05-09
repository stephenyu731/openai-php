<?php

namespace SH\OpenAI\Model\Response;

class TranscriptionWord
{
    public $word;
    public $start;
    public $end;

    public function __construct($data = [])
    {
        $this->word = $data['word'] ?? null;
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
    }
}
