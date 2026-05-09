<?php

namespace SH\OpenAI\Model\Response;

class Transcription
{
    public $task;
    public $language;
    public $duration;
    public $text;
    public $segments = [];
    public $words = [];

    public function __construct($data = [])
    {
        $this->task = $data['task'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->duration = $data['duration'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->segments = array_map(function ($seg) {
            return new TranscriptionSegment($seg);
        }, $data['segments'] ?? []);
        $this->words = array_map(function ($word) {
            return new TranscriptionWord($word);
        }, $data['words'] ?? []);
    }
}
