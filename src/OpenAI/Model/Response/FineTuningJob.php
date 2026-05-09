<?php

namespace SH\OpenAI\Model\Response;

class FineTuningJob
{
    public $id;
    public $object;
    public $model;
    public $createdAt;
    public $finishedAt;
    public $fineTunedModel;
    public $organizationId;
    public $resultFiles;
    public $status;
    public $validationFile;
    public $trainingFile;
    public $trainedTokens;
    public $hyperparameters;
    public $error;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->object = $data['object'] ?? null;
        $this->model = $data['model'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->finishedAt = $data['finished_at'] ?? null;
        $this->fineTunedModel = $data['fine_tuned_model'] ?? null;
        $this->organizationId = $data['organization_id'] ?? null;
        $this->resultFiles = $data['result_files'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->validationFile = $data['validation_file'] ?? null;
        $this->trainingFile = $data['training_file'] ?? null;
        $this->trainedTokens = $data['trained_tokens'] ?? null;
        $this->hyperparameters = $data['hyperparameters'] ?? null;
        $this->error = $data['error'] ?? null;
    }
}
