# OpenAI PHP

[![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](./LICENSE)

A lightweight, zero-dependency PHP HTTP client for the full [OpenAI API v1](https://platform.openai.com/docs/api-reference). Uses plain cURL — no Guzzle or other third-party HTTP libraries.

## Requirements

- PHP >= 8.0
- ext-curl
- ext-json

## Installation

```bash
composer require stephenyu731/openai-php
```

## Quick Start

```php
use SH\OpenAI\Client;

$client = new Client('sk-...');
```

### Chat Completions

```php
$result = $client->completions([
    'model'    => 'gpt-4o',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!'],
    ],
]);

echo $result->getAnswerContent();
```

### Streaming

```php
$chunks = $client->completionsStreamed([
    'model'    => 'gpt-4o',
    'messages' => [
        ['role' => 'user', 'content' => 'Tell me a story.'],
    ],
]);

foreach ($chunks as $chunk) {
    echo $chunk['choices'][0]['delta']['content'] ?? '';
}
```

### Responses API (New)

```php
$response = $client->responsesCreate([
    'model' => 'gpt-4o',
    'input' => 'What is the weather today?',
]);

echo $response->output[0]->content[0]->text;

// Streaming
$chunks = $client->responsesCreateStreamed([
    'model' => 'gpt-4o',
    'input' => 'Write a poem.',
]);
```

### Multi-turn Chat with Tool Calls

```php
use SH\OpenAI\Chat;

$chat = new Chat($client);

$chat->setTools([
    [
        'type'     => 'function',
        'function' => [
            'name'        => 'get_weather',
            'parameters'  => [
                'type'       => 'object',
                'properties' => [
                    'location' => ['type' => 'string'],
                ],
            ],
        ],
    ],
]);

$result = $chat->addMessage("What's the weather in Tokyo?");

if ($result->isToolCalls()) {
    $toolCall = $result->choices[0]->message->tool_calls[0];
    $chat->addToolCallResult($toolCall->id, 'Sunny, 25°C', 'get_weather');
    $final = $chat->addMessage('Summarize the weather.');
    echo $final->getAnswerContent();
}
```

### Embeddings

```php
$emb = $client->embeddingsCreate([
    'model' => 'text-embedding-3-small',
    'input' => 'Hello world',
]);

$vector = $emb->data[0]->embedding;
```

### Images

```php
$images = $client->imagesCreate([
    'model'  => 'dall-e-3',
    'prompt' => 'A futuristic city skyline at sunset',
]);

echo $images->data[0]->url;
```

### Audio

```php
// Speech (TTS)
$audio = $client->audioSpeech([
    'model' => 'tts-1',
    'input' => 'Hello, world!',
    'voice' => 'alloy',
]);

// Transcription
$transcription = $client->audioTranscribe([
    'model' => 'whisper-1',
    'file'  => '/path/to/audio.mp3',
]);
```

## API Coverage

| Resource         | Methods |
|------------------|---------|
| **Models**       | `modelsList()` `modelsRetrieve($id)` `modelsDelete($id)` |
| **Chat**         | `completions($opts)` `completionsStreamed($opts)` |
| **Responses**    | `responsesCreate($opts)` `responsesCreateStreamed($opts)` `responsesRetrieve($id)` `responsesCancel($id)` `responsesDelete($id)` `responsesList($id)` |
| **Audio**        | `audioSpeech($opts)` `audioTranscribe($opts)` `audioTranslate($opts)` |
| **Embeddings**   | `embeddingsCreate($opts)` |
| **Images**       | `imagesCreate($opts)` `imagesEdit($opts)` `imagesVariation($opts)` |
| **Files**        | `filesList($opts)` `filesRetrieve($id)` `filesUpload($opts)` `filesDelete($id)` `filesDownload($id)` |
| **Moderations**  | `moderationsCreate($opts)` |
| **Fine-Tuning**  | `fineTuningCreateJob($opts)` `fineTuningListJobs($opts)` `fineTuningRetrieveJob($id)` `fineTuningCancelJob($id)` `fineTuningListJobEvents($id)` |

All options arrays are passed directly as JSON to the OpenAI API.

## Configuration

```php
$client = new Client('sk-...');

// Custom base URL (e.g., Azure OpenAI, proxies)
$client->setBaseURL('https://your-proxy.example.com/v1');

// Model override
$client->setModel('gpt-4o');

// Proxy
$client->setProxy('http://proxy.example.com:8080');
```

## Development

```bash
docker compose up -d
docker compose run php bash
```

```bash
composer install
vendor/bin/phpunit
```

## Testing

```bash
vendor/bin/phpunit
```

## License

MIT
