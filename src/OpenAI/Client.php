<?php

namespace SH\OpenAI;

use SH\OpenAI\Model\Response\Completions;

class Client
{
    private $apiKey;
    protected $baseUrl = "https://api.openai.com";

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

    public function getMdoel()
    {
        return $this->model;
    }

    protected function request($endpoint, $data = [])
    {

        // Initialize cURL
        $ch = curl_init("{$this->baseUrl}{$endpoint}");

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->apiKey}",
            "Content-Type: application/json"
        ]);
        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy ?? '');
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the request
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            throw new Exception("http error: {$error}");
        }

        $responseData = json_decode($response, 1);
        if (!$responseData) {
            throw new Exception("invalid response content format");
        }

        if (isset($responseData['error'])) {
            throw new Exception($responseData['error']['message']);
        }

        return $responseData;
    }

    /**
     * @return Completions
     */
    public function completions($opts = [])
    {
        if (!isset($opts['model'])) {
            $opts['model'] = $this->model;
        }

        $response = $this->request('/v1/chat/completions', $opts);

        $completionsResponse = new Completions($response);

        return $completionsResponse;
    }
}
