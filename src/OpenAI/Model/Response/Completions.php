<?php

namespace SH\OpenAI\Model\Response;

class Completions
{
    public $id;
    public $provider;
    public $object;
    public $model;
    public $created;
    public $systemFingerprint;
    public $serviceTier;
    /**
     * @var Choice[]
     */
    public $choices = [];
    /**
     * @var Usage|null
     */
    public $usage;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->provider = $data['provider'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->systemFingerprint = $data['system_fingerprint'] ?? null;
        $this->serviceTier = $data['service_tier'] ?? null;
        $this->choices = array_map(function ($choice) {
            return new Choice($choice);
        }, $data['choices'] ?? []);
        $this->usage = isset($data['usage']) ? new Usage($data['usage']) : null;
    }

    /**
     * @return string|null
     */
    public function getAnswerContent()
    {
        if (empty($this->choices) || !$this->choices[0]->message) {
            return null;
        }
        return $this->choices[0]->message->content;
    }

    /**
     * @return Message|null
     */
    public function getAnswerMessage()
    {
        if (empty($this->choices) || !$this->choices[0]->message) {
            return null;
        }
        return $this->choices[0]->message;
    }

    /**
     * @return bool
     */
    public function isToolCalls()
    {
        if (empty($this->choices)) {
            return false;
        }
        return $this->choices[0]->finish_reason === 'tool_calls';
    }

    /**
     * @return bool
     */
    public function isFunctionCall()
    {
        if (empty($this->choices)) {
            return false;
        }
        return $this->choices[0]->finish_reason === 'function_call';
    }
}
