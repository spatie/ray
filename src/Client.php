<?php

namespace Spatie\Ray;

use Exception;
use Spatie\Ray\Exceptions\CouldNotConnectToRay;
use Spatie\Ray\Exceptions\StopExecutionRequested;

class Client
{
    protected int $portNumber;

    protected string $host;

    public function __construct(int $portNumber = 23517, string $host = 'localhost')
    {
        $this->portNumber = $portNumber;

        $this->host = $host;
    }

    public function send(Request $request): void
    {
        $curlHandle = $this->getCurlHandleForUrl('get', '');

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request->toJson());

        try {
            curl_exec($curlHandle);
        } catch (Exception $exception) {
            throw new Exception("Ray seems not be running at http://{$this->host}:{$this->portNumber}");
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

            if ($response['stop_execution'] ?? false) {
                throw StopExecutionRequested::make();
            }

            return $response['active'] ?? false;
        } catch (Exception $exception) {
            if ($exception instanceof  StopExecutionRequested) {
                throw $exception;
            }

            throw CouldNotConnectToRay::make($this->host, $this->portNumber);
        }
    }

    protected function getCurlHandleForUrl(string $method, string $url)
    {
        return $this->getCurlHandle($method, "http://{$this->host}:{$this->portNumber}/{$url}");
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
