<?php

namespace SH\OpenAI\Model\Response;

class ImageData
{
    public $url;
    public $b64Json;
    public $revisedPrompt;

    public function __construct($data = [])
    {
        $this->url = $data['url'] ?? null;
        $this->b64Json = $data['b64_json'] ?? null;
        $this->revisedPrompt = $data['revised_prompt'] ?? null;
    }
}
