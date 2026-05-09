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
        $data = [
            'messages' => $this->messages,
        ];
        if ($this->tools) {
            $data['tools'] = $this->tools;
        }
        $response = $this->client->completions($data);

        $this->messages[] = [
            'role' => Role::ASSISTANT,
            'content' => $response->getAnswerContent(),
        ];
        return $response;
    }

    public function addToolCallResult($toolCallId, $result, $toolName = null)
    {
        $this->messages[] = [
            'role' => Role::TOOL,
            'tool_call_id' => $toolCallId,
            'name' => $toolName,
            'content' => $result,
        ];
        $data = [
            'messages' => $this->messages,
        ];
        if ($this->tools) {
            $data['tools'] = $this->tools;
        }
        $response = $this->client->completions($data);

        $this->messages[] = [
            'role' => Role::ASSISTANT,
            'content' => $response->getAnswerContent(),
        ];
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
