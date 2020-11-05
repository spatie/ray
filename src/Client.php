<?php

namespace Spatie\Timber;

class Client
{
    protected string $baseUrl;

    protected int $portNumber;

    public function __construct(string $baseUrl = 'http://localhost', int $portNumber = 23517)
    {
        $this->baseUrl = $baseUrl;

        $this->portNumber = $portNumber;
    }

    public function send(Request $request)
    {
        $curlHandle = $this->getCurlHandle("{$this->baseUrl}:{$this->portNumber}");

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request->toJson());

        curl_exec($curlHandle);
    }

    protected function getCurlHandle(string $fullUrl)
    {
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $fullUrl);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array_merge([
            'Accept: application/json',
            'Content-Type: application/json',
        ]));

        curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Timber 1.0');
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curlHandle, CURLOPT_POST, true);

        return $curlHandle;
    }
}
