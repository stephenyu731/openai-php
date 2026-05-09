<?php

namespace SH\OpenAI\Model;

class Model
{
    public $id;
    public $object;
    public $created;
    public $ownedBy;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->ownedBy = $data['owned_by'] ?? null;
    }
}
