<?php

namespace SH\OpenAI;

use SH\OpenAI\Model\Response\Completions;

class Client
{
    private $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';

    private $proxy;

    private $model;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    // ─── HTTP Layer ───────────────────────────────────────────────────

    protected function request($method, $endpoint, $data = [], $contentType = 'application/json')
    {
        $url = "{$this->baseUrl}{$endpoint}";
        $ch = curl_init($url);

        $headers = [
            "Authorization: Bearer {$this->apiKey}",
            "Content-Type: {$contentType}",
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
                }
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            throw new Exception("http error: {$error}", $statusCode);
        }

        $responseData = json_decode($response, true);
        if (!$responseData) {
            throw new Exception('invalid response content format', $statusCode);
        }

        if (isset($responseData['error'])) {
            throw new Exception($responseData['error']['message'], $statusCode);
        }

        return $responseData;
    }

    /**
     * Streaming SSE request. Returns all chunks as an array.
     */
    protected function requestStream($endpoint, $data = [])
    {
        $chunks = [];
        $buffer = '';

        $ch = curl_init("{$this->baseUrl}{$endpoint}");
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$this->apiKey}",
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_WRITEFUNCTION  => function ($ch, $data) use (&$chunks, &$buffer) {
                $buffer .= $data;
                while (($pos = strpos($buffer, "\n\n")) !== false) {
                    $event = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + 2);
                    foreach (explode("\n", $event) as $line) {
                        if (str_starts_with($line, 'data: ')) {
                            $json = substr($line, 6);
                            if ($json === '[DONE]') {
                                break 2;
                            }
                            $decoded = json_decode($json, true);
                            if ($decoded) {
                                $chunks[] = $decoded;
                            }
                        }
                    }
                }
                return strlen($data);
            },
        ]);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            throw new Exception("http error: {$error}", $statusCode);
        }

        if ($statusCode >= 400) {
            throw new Exception("stream request failed with status {$statusCode}.", $statusCode);
        }

        return $chunks;
    }

    /**
     * Multipart form-data request (for file/audio uploads).
     */
    protected function requestMultipart($endpoint, $data = [])
    {
        $url = "{$this->baseUrl}{$endpoint}";
        $ch = curl_init($url);

        $postFields = [];
        foreach ($data as $key => $value) {
            if (is_resource($value)) {
                $postFields[$key] = new \CURLFile(stream_get_meta_data($value)['uri']);
            } else {
                $postFields[$key] = $value;
            }
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$this->apiKey}",
            ],
        ]);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            throw new Exception("http error: {$error}", $statusCode);
        }

        $responseData = json_decode($response, true);
        if (!$responseData) {
            throw new Exception('invalid response content format', $statusCode);
        }

        if (isset($responseData['error'])) {
            throw new Exception($responseData['error']['message'], $statusCode);
        }

        return $responseData;
    }

    /**
     * Request that returns raw binary content (e.g. audio speech).
     */
    protected function requestRaw($endpoint, $data = [])
    {
        $url = "{$this->baseUrl}{$endpoint}";
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$this->apiKey}",
                'Content-Type: application/json',
            ],
        ]);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            throw new Exception("http error: {$error}", $statusCode);
        }

        if ($statusCode >= 400) {
            $decoded = json_decode($response, true);
            if (isset($decoded['error']['message'])) {
                throw new Exception($decoded['error']['message'], $statusCode);
            }
            throw new Exception("request failed with status {$statusCode}", $statusCode);
        }

        return $response;
    }

    // ─── Models ───────────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\ListResponse
     */
    public function modelsList($opts = [])
    {
        $data = $this->request('GET', '/models', $opts);
        return new Model\Response\ListResponse($data);
    }

    /**
     * @param  string $modelId
     * @return Model\Model
     */
    public function modelsRetrieve($modelId)
    {
        $data = $this->request('GET', "/models/{$modelId}");
        return new Model\Model($data);
    }

    /**
     * @param  string $modelId
     * @return array
     */
    public function modelsDelete($modelId)
    {
        return $this->request('DELETE', "/models/{$modelId}");
    }

    // ─── Chat Completions ─────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Completions
     */
    public function completions($opts = [])
    {
        if (!isset($opts['model'])) {
            $opts['model'] = $this->model;
        }
        if (!isset($opts['stream']) || !$opts['stream']) {
            $response = $this->request('POST', '/chat/completions', $opts);
            return new Completions($response);
        }
        return $this->requestStream('/chat/completions', $opts);
    }

    /**
     * @param  array $opts
     * @return array
     */
    public function completionsStreamed($opts = [])
    {
        if (!isset($opts['model'])) {
            $opts['model'] = $this->model;
        }
        $opts['stream'] = true;
        return $this->requestStream('/chat/completions', $opts);
    }

    // ─── Responses API (new) ──────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\Response
     */
    public function responsesCreate($opts = [])
    {
        if (!isset($opts['model']) && $this->model) {
            $opts['model'] = $this->model;
        }
        $data = $this->request('POST', '/responses', $opts);
        return new Model\Response\Response($data);
    }

    /**
     * @param  array $opts
     * @return array
     */
    public function responsesCreateStreamed($opts = [])
    {
        if (!isset($opts['model']) && $this->model) {
            $opts['model'] = $this->model;
        }
        $opts['stream'] = true;
        return $this->requestStream('/responses', $opts);
    }

    /**
     * @param  string $responseId
     * @param  array  $opts
     * @return Model\Response\Response
     */
    public function responsesRetrieve($responseId, $opts = [])
    {
        $data = $this->request('GET', "/responses/{$responseId}", $opts);
        return new Model\Response\Response($data);
    }

    /**
     * @param  string $responseId
     * @param  array  $opts
     * @return array
     */
    public function responsesRetrieveStreamed($responseId, $opts = [])
    {
        return $this->requestStream("/responses/{$responseId}", $opts);
    }

    /**
     * @param  string $responseId
     * @return Model\Response\Response
     */
    public function responsesCancel($responseId)
    {
        $data = $this->request('POST', "/responses/{$responseId}/cancel");
        return new Model\Response\Response($data);
    }

    /**
     * @param  string $responseId
     * @return array
     */
    public function responsesDelete($responseId)
    {
        return $this->request('DELETE', "/responses/{$responseId}");
    }

    /**
     * @param  string $responseId
     * @param  array  $opts
     * @return Model\Response\ListResponse
     */
    public function responsesList($responseId, $opts = [])
    {
        $data = $this->request('GET', "/responses/{$responseId}/input_items", $opts);
        return new Model\Response\ListResponse($data);
    }

    // ─── Audio ────────────────────────────────────────────────────────

    /**
     * @param  array $opts
     * @return string Raw audio binary
     */
    public function audioSpeech($opts = [])
    {
        return $this->requestRaw('/audio/speech', $opts);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\Transcription
     */
    public function audioTranscribe($opts = [])
    {
        $data = $this->requestMultipart('/audio/transcriptions', $opts);
        return new Model\Response\Transcription($data);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\Transcription
     */
    public function audioTranslate($opts = [])
    {
        $data = $this->requestMultipart('/audio/translations', $opts);
        return new Model\Response\Transcription($data);
    }

    // ─── Embeddings ───────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\EmbeddingResponse
     */
    public function embeddingsCreate($opts = [])
    {
        if (!isset($opts['model']) && $this->model) {
            $opts['model'] = $this->model;
        }
        $data = $this->request('POST', '/embeddings', $opts);
        return new Model\Response\EmbeddingResponse($data);
    }

    // ─── Images ───────────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\ImageResponse
     */
    public function imagesCreate($opts = [])
    {
        if (!isset($opts['model']) && $this->model) {
            $opts['model'] = $this->model;
        }
        $data = $this->request('POST', '/images/generations', $opts);
        return new Model\Response\ImageResponse($data);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\ImageResponse
     */
    public function imagesEdit($opts = [])
    {
        $data = $this->requestMultipart('/images/edits', $opts);
        return new Model\Response\ImageResponse($data);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\ImageResponse
     */
    public function imagesVariation($opts = [])
    {
        $data = $this->requestMultipart('/images/variations', $opts);
        return new Model\Response\ImageResponse($data);
    }

    // ─── Files ────────────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\ListResponse
     */
    public function filesList($opts = [])
    {
        $data = $this->request('GET', '/files', $opts);
        return new Model\Response\ListResponse($data);
    }

    /**
     * @param  string $fileId
     * @return Model\Response\FileResponse
     */
    public function filesRetrieve($fileId)
    {
        $data = $this->request('GET', "/files/{$fileId}");
        return new Model\Response\FileResponse($data);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\FileResponse
     */
    public function filesUpload($opts = [])
    {
        $data = $this->requestMultipart('/files', $opts);
        return new Model\Response\FileResponse($data);
    }

    /**
     * @param  string $fileId
     * @return array
     */
    public function filesDelete($fileId)
    {
        return $this->request('DELETE', "/files/{$fileId}");
    }

    /**
     * @param  string $fileId
     * @return string Raw file content
     */
    public function filesDownload($fileId)
    {
        $url = "{$this->baseUrl}/files/{$fileId}/content";
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$this->apiKey}",
            ],
        ]);

        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            throw new Exception("http error: {$error}", $statusCode);
        }

        if ($statusCode >= 400) {
            $decoded = json_decode($response, true);
            if (isset($decoded['error']['message'])) {
                throw new Exception($decoded['error']['message'], $statusCode);
            }
            throw new Exception("file download failed with status {$statusCode}", $statusCode);
        }

        return $response;
    }

    // ─── Moderations ──────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\ModerationResponse
     */
    public function moderationsCreate($opts = [])
    {
        if (!isset($opts['model']) && $this->model) {
            $opts['model'] = $this->model;
        }
        $data = $this->request('POST', '/moderations', $opts);
        return new Model\Response\ModerationResponse($data);
    }

    // ─── Fine-Tuning ──────────────────────────────────────────────────

    /**
     * @param  array  $opts
     * @return Model\Response\FineTuningJob
     */
    public function fineTuningCreateJob($opts = [])
    {
        $data = $this->request('POST', '/fine_tuning/jobs', $opts);
        return new Model\Response\FineTuningJob($data);
    }

    /**
     * @param  array  $opts
     * @return Model\Response\ListResponse
     */
    public function fineTuningListJobs($opts = [])
    {
        $data = $this->request('GET', '/fine_tuning/jobs', $opts);
        return new Model\Response\ListResponse($data);
    }

    /**
     * @param  string $jobId
     * @return Model\Response\FineTuningJob
     */
    public function fineTuningRetrieveJob($jobId)
    {
        $data = $this->request('GET', "/fine_tuning/jobs/{$jobId}");
        return new Model\Response\FineTuningJob($data);
    }

    /**
     * @param  string $jobId
     * @return Model\Response\FineTuningJob
     */
    public function fineTuningCancelJob($jobId)
    {
        $data = $this->request('POST', "/fine_tuning/jobs/{$jobId}/cancel");
        return new Model\Response\FineTuningJob($data);
    }

    /**
     * @param  string $jobId
     * @param  array  $opts
     * @return Model\Response\ListResponse
     */
    public function fineTuningListJobEvents($jobId, $opts = [])
    {
        $data = $this->request('GET', "/fine_tuning/jobs/{$jobId}/events", $opts);
        return new Model\Response\ListResponse($data);
    }
}
