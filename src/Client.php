<?php

namespace Spatie\Ray;

use Exception;
use Spatie\Ray\Exceptions\StopExecutionRequested;

class Client
{
    protected static $cache = [];

    /** @var int */
    protected $portNumber;

    /** @var string */
    protected $host;

    /** @var string */
    protected $fingerprint;

    public function __construct(int $portNumber = 23517, string $host = 'localhost')
    {
        $this->fingerprint = md5($this->host . ':' . $this->portNumber);

        $this->portNumber = $portNumber;

        $this->host = $host;
    }

    public function serverIsAvailable(): bool
    {
        static::$cache = array_filter(static::$cache, function($data) {
            return microtime(true) < $data[1];
        });

        if (! isset(static::$cache[$this->fingerprint])) {
            $this->performAvailabilityCheck();
        }

        return static::$cache[$this->fingerprint][0];
    }

    public function performAvailabilityCheck()
    {
        try {
            $curlHandle = $this->getCurlHandleForUrl('get', 'locks/___' . random_int(1000, 999999));

            curl_exec($curlHandle);

            $success = curl_errno($curlHandle) === CURLE_OK;
            $expiresAt = microtime(true) + 10.0; // expire the availability after 10 sec

            static::$cache[$this->fingerprint] = [$success, $expiresAt];

        } finally {
            curl_close($curlHandle);
        }
    }

    public function send(Request $request): void
    {
        if (! $this->serverIsAvailable()) {
            print_r(['server not available, not sending']);
            return;
        }

        try {
            $curlHandle = $this->getCurlHandleForUrl('get', '');

            $curlError = null;

            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request->toJson());
            curl_exec($curlHandle);

            if (curl_errno($curlHandle)) {
                $curlError = curl_error($curlHandle);
            }

            if ($curlError) {
                // do nothing for now
            }
        } finally {
            curl_close($curlHandle);
        }
    }

    public function lockExists(string $lockName): bool
    {
        if (! $this->serverIsAvailable()) {
            print_r(['server not available, not sending']);
            return false;
        }

        $curlHandle = $this->getCurlHandleForUrl('get', "locks/{$lockName}");
        $curlError = null;

        try {
            $curlResult = curl_exec($curlHandle);

            if (curl_errno($curlHandle)) {
                $curlError = curl_error($curlHandle);
            }

            if ($curlError) {
                throw new Exception;
            }

            if (! $curlResult) {
                return false;
            }

            $response = json_decode($curlResult, true);

            if ($response['stop_execution'] ?? false) {
                throw StopExecutionRequested::make();
            }

            return $response['active'] ?? false;
        } catch (Exception $exception) {
            if ($exception instanceof StopExecutionRequested) {
                throw $exception;
            }
        } finally {
            curl_close($curlHandle);
        }

        return false;
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
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);

        if ($method === 'post') {
            curl_setopt($curlHandle, CURLOPT_POST, true);
        }

        return $curlHandle;
    }
}
