<?php

namespace SH\OpenAI\Model\Response;

class Response
{
    public $id;
    public $object;
    public $createdAt;
    public $status;
    public $statusDetails;
    public $error;
    public $incompleteDetails;
    public $instructions;
    public $maxOutputTokens;
    public $model;
    public $output = [];
    public $parallelToolCalls;
    public $previousResponseId;
    public $reasoning;
    public $store;
    public $temperature;
    public $text;
    public $toolChoice;
    public $tools;
    public $topP;
    public $truncation;
    public $usage;
    public $user;
    public $metadata;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->statusDetails = $data['status_details'] ?? null;
        $this->error = $data['error'] ?? null;
        $this->incompleteDetails = $data['incomplete_details'] ?? null;
        $this->instructions = $data['instructions'] ?? null;
        $this->maxOutputTokens = $data['max_output_tokens'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->output = array_map(function ($item) {
            return new ResponseOutput($item);
        }, $data['output'] ?? []);
        $this->parallelToolCalls = $data['parallel_tool_calls'] ?? null;
        $this->previousResponseId = $data['previous_response_id'] ?? null;
        $this->reasoning = $data['reasoning'] ?? null;
        $this->store = $data['store'] ?? null;
        $this->temperature = $data['temperature'] ?? null;
        $this->text = $data['text'] ?? null;
        $this->toolChoice = $data['tool_choice'] ?? null;
        $this->tools = $data['tools'] ?? null;
        $this->topP = $data['top_p'] ?? null;
        $this->truncation = $data['truncation'] ?? null;
        $this->usage = isset($data['usage']) ? new ResponseUsage($data['usage']) : null;
        $this->user = $data['user'] ?? null;
        $this->metadata = $data['metadata'] ?? null;
    }
}
