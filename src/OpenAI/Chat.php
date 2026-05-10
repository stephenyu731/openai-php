<?php

namespace SH\OpenAI;

use SH\OpenAI\Enum\Role;

class Chat
{
    /**
     * @var Client
     */
    private $client;

    private $messages = [];

    private $tools;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setTools($tools)
    {
        $this->tools = $tools;
        return $this;
    }

    public function addMessage($message, $role = Role::USER)
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $message,
        ];
        return $this->sendAndRecordResponse();
    }

    public function addToolCallResult($toolCallId, $result, $toolName = null)
    {
        $this->messages[] = [
            'role' => Role::TOOL,
            'tool_call_id' => $toolCallId,
            'name' => $toolName,
            'content' => $result,
        ];
        return $this->sendAndRecordResponse();
    }

    private function sendAndRecordResponse()
    {
        $data = ['messages' => $this->messages];
        if ($this->tools) {
            $data['tools'] = $this->tools;
        }
        $response = $this->client->completions($data);

        $assistantMessage = ['role' => Role::ASSISTANT];
        $responseMessage = $response->getAnswerMessage();
        if ($responseMessage && ($response->isToolCalls() || $response->isFunctionCall())) {
            $assistantMessage['content'] = $responseMessage->content;
            if (!empty($responseMessage->tool_calls)) {
                $assistantMessage['tool_calls'] = array_map(function ($tc) {
                    return [
                        'id' => $tc->id,
                        'type' => $tc->type,
                        'function' => [
                            'name' => $tc->function->name,
                            'arguments' => $tc->function->arguments,
                        ],
                    ];
                }, $responseMessage->tool_calls);
            }
        } else {
            $assistantMessage['content'] = $response->getAnswerContent();
        }
        $this->messages[] = $assistantMessage;

        return $response;
    }

    public function reset()
    {
        $this->messages = [];
        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
