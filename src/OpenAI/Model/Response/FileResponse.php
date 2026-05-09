<?php

namespace SH\OpenAI\Model\Response;

class FileResponse
{
    public $id;
    public $object;
    public $bytes;
    public $createdAt;
    public $filename;
    public $purpose;
    public $status;
    public $statusDetails;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->bytes = $data['bytes'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->filename = $data['filename'] ?? null;
        $this->purpose = $data['purpose'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->statusDetails = $data['status_details'] ?? null;
    }
}
