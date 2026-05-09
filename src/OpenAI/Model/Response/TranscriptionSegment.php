<?php

namespace SH\OpenAI\Model\Response;

class TranscriptionSegment
{
    public $id;
    public $seek;
    public $start;
    public $end;
    public $text;
    public $tokens;
    public $temperature;
    public $avgLogprob;
    public $compressionRatio;
    public $noSpeechProb;
    public $transient;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->seek = $data['seek'] ?? null;
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->tokens = $data['tokens'] ?? null;
        $this->temperature = $data['temperature'] ?? null;
        $this->avgLogprob = $data['avg_logprob'] ?? null;
        $this->compressionRatio = $data['compression_ratio'] ?? null;
        $this->noSpeechProb = $data['no_speech_prob'] ?? null;
        $this->transient = $data['transient'] ?? null;
    }
}
