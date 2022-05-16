<?php

namespace RevelPHP\Tests\Factories;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RevelPHP\Connection;

final class ConnectionFactory
{
    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    private string $baseUri;

    private string $apiKey;

    private string $apiSecret;

    public static function new(): self
    {
        return new self;
    }

    public function client(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function requestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    public function streamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    public function baseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function apiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function apiSecret(string $apiSecret): self
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function make(): Connection
    {
        $connection = new Connection(
            httpClient: $this->client ?? Psr18ClientDiscovery::find(),
            requestFactory: $this->requestFactory ?? Psr17FactoryDiscovery::findRequestFactory(),
            streamFactory: $this->streamFactory ?? Psr17FactoryDiscovery::findStreamFactory()
        );

        if (isset($this->baseUri)) {
            $connection->setBaseUri($this->baseUri);
        }

        if (isset($this->apiKey)) {
            $connection->setApiKey($this->apiKey);
        }

        if (isset($this->apiSecret)) {
            $connection->setApiSecret($this->apiSecret);
        }

        return $connection;
    }
}