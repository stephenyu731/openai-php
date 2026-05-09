<?php

namespace SH\OpenAI\Tests\Model;

use PHPUnit\Framework\TestCase;
use SH\OpenAI\Model\Model;

class ModelTest extends TestCase
{
    public function testConstructWithValidData()
    {
        $data = [
            'id' => 'gpt-4',
            'object' => 'model',
            'created' => 1686935002,
            'owned_by' => 'openai',
        ];
        $model = new Model($data);

        $this->assertSame('gpt-4', $model->id);
        $this->assertSame('model', $model->object);
        $this->assertSame(1686935002, $model->created);
        $this->assertSame('openai', $model->ownedBy);
    }

    public function testConstructWithEmptyData()
    {
        $model = new Model([]);

        $this->assertNull($model->id);
        $this->assertNull($model->object);
        $this->assertNull($model->created);
        $this->assertNull($model->ownedBy);
    }

    public function testConstructWithPartialData()
    {
        $model = new Model(['id' => 'davinci']);

        $this->assertSame('davinci', $model->id);
        $this->assertNull($model->object);
        $this->assertNull($model->created);
        $this->assertNull($model->ownedBy);
    }
}
