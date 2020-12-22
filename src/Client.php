<?php

namespace Spatie\Ray;

use Exception;

class Client
{
    protected int $portNumber;

    protected string $baseUrl;

    public function __construct(int $portNumber = 23517, string $baseUrl = 'http://localhost')
    {
        $this->portNumber = $portNumber;

        $this->baseUrl = $baseUrl;
    }

    public function send(Request $request): void
    {
        $curlHandle = $this->getCurlHandleForUrl('get', '');

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request->toJson());

        try {
            curl_exec($curlHandle);
        } catch (Exception $exception) {
            throw new Exception("Ray seems not be running at {$this->baseUrl}:{$this->portNumber}");
        }
    }

    public function lockExists(string $lockName): bool
    {
        $curlHandle = $this->getCurlHandleForUrl('get', "locks/{$lockName}");

        try {
            $curlResult = curl_exec($curlHandle);

            if (! $curlResult) {
                return false;
            }

            $response = json_decode($curlResult, true);

            return $response['active'] ?? false;
        } catch (Exception $exception) {
            throw new Exception("Ray seems not be running at {$this->baseUrl}:{$this->portNumber}");
        }
    }

    protected function getCurlHandleForUrl(string $method, string $url)
    {
        return $this->getCurlHandle($method, "{$this->baseUrl}:{$this->portNumber}/{$url}");
    }

    protected function getCurlHandle(string $method, string $fullUrl)
    {
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $fullUrl);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array_merge([
            'Accept: application/json',
            'Content-Type: application/json',
        ]));

        curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Ray 1.0');
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);

        if ($method === 'post') {
            curl_setopt($curlHandle, CURLOPT_POST, true);
        }

        return $curlHandle;
    }
}
