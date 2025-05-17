<?php

namespace SH\OpenAI\Model\Response;

class Completions
{
    public $id;
    public $provider;
    public $model;
    public $object;
    public $created;
    /**
     * @var Choice array
     */
    public $choices = [];
    /**
     * 
     * @var Usage
     */
    public $usage;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->provider = $data['provider'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->choices = array_map(function ($choice) {
            return new Choice($choice);
        }, $data['choices'] ?? []);
        $this->usage = new Usage($data['usage'] ?? []);
    }

    /**
     * @return string
     */
    public function getAnswerContent()
    {
        return $this->choices[0]->message->content;
    }


    /**
     * @return boolean
     */
    public function isToolCalls()
    {
        return $this->choices[0]->finish_reason == 'tool_calls';
    }
}
