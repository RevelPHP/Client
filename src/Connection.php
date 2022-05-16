<?php

namespace RevelPHP;

use Exception;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

final class Connection
{
    private string $baseUri;

    private string $apiKey;

    private string $apiSecret;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory
    ) {}

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri ?? throw new Exception(
            'No base uri was set. Did you forget to call setBaseUri()?'
        );
    }

    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey ?? throw new Exception(
            'No api key was set. Did you forget to call setApiKey()?'
        );
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getApiSecret(): string
    {
        return $this->apiSecret ?? throw new Exception(
            'No api secret was set. Did you forget to call setApiSecret()?'
        );
    }

    public function setApiSecret(string $apiSecret): self
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function get(string $endpoint, array $query = [], array $headers = []): ResponseInterface
    {
        $endpoint = $this->normalizeEndpoint($endpoint, $query);

        return $this->send('GET', $endpoint, $headers);
    }

    public function post(string $endpoint, null|string|StreamInterface $body = null, array $headers = []): ResponseInterface
    {
        $endpoint = $this->normalizeEndpoint($endpoint);

        return $this->send('POST', $endpoint, $headers, $body);
    }

    public function send(string $method, string $endpoint, array $headers = [], null|string|StreamInterface $body = null): ResponseInterface
    {
        $uri = "{$this->getBaseUri()}/$endpoint";
        $request = $this->requestFactory->createRequest($method, $uri);
        $headers = array_replace_recursive($headers, [
            'API-AUTHENTICATION' => "{$this->getApiKey()}:{$this->getApiSecret()}",
            'Content-Type' => 'application/json',
        ]);

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        if ($body) {
            $body = is_string($body) ? $this->streamFactory->createStream($body) : $body;

            $request = $request->withBody($body);
        }

        return $this->httpClient->sendRequest($request);
    }

    private function normalizeEndpoint(string $endpoint, array $query = []): string
    {
        $endpoint = trim($endpoint, '/');

        if (! str_contains($endpoint, '?')) {
            $endpoint .= '/';
        }

        if (! empty($query)) {
            $endpoint .= '?' . http_build_query(data: $query, encoding_type: PHP_QUERY_RFC3986);
        }

        return $endpoint;
    }
}