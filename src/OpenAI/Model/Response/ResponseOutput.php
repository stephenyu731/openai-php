<?php

namespace SH\OpenAI\Model\Response;

class ResponseOutput
{
    public $type;
    public $id;
    public $status;
    public $role;
    public $content = [];
    public $name;
    public $arguments;
    public $callId;

    public function __construct($data = [])
    {
        $this->type = $data['type'] ?? null;
        $this->id = $data['id'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->content = array_map(function ($item) {
            return new ResponseContent($item);
        }, $data['content'] ?? []);
        $this->name = $data['name'] ?? null;
        $this->arguments = $data['arguments'] ?? null;
        $this->callId = $data['call_id'] ?? null;
    }
}
