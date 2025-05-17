<?php

namespace SH\OpenAI;

use SH\OpenAI\Enum\Role;

class Client
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

    public function addMessage($message, $role = Role::USER)
    {
        array_push($this->messages, [
            'role' => $role,
            'content' => $message
        ]);
        $data = [
            'messages' => $this->messages,
            'tools' => $this->tools,
        ];
        $response = $this->client->completions($data);

        array_push($this->messages, [
            'role' => Role::SYSTEM,
            'content' => $response->getAnswerContent()
        ]);
        return $response;
    }
}
