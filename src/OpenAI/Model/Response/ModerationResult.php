<?php

namespace SH\OpenAI\Model\Response;

class ModerationResult
{
    public $flagged;
    public $categories;
    public $categoryScores;

    public function __construct($data = [])
    {
        $this->flagged = $data['flagged'] ?? null;
        $this->categories = isset($data['categories']) ? new ModerationCategory($data['categories']) : null;
        $this->categoryScores = isset($data['category_scores']) ? new ModerationCategory($data['category_scores']) : null;
    }
}
