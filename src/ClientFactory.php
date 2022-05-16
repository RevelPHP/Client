<?php

namespace RevelPHP;

use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ClientFactory
{
    private string $baseUri;

    private string $apiKey;

    private string $apiSecret;

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public static function new()
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

    public function make(): Client
    {
        $client = $this->client ?? Psr18ClientDiscovery::find();
        $requestFactory = $this->requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $streamFactory = $this->streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $baseUri = $this->baseUri ?? throw new Exception('No base uri was set. Did you forget to call baseUri()?');
        $apiKey = $this->apiKey ?? throw new Exception('No api key was set. Did you forget to call apiKey()?');
        $apiSecret = $this->apiSecret ?? throw new Exception('No api secret was set. Did you forget to call apiSecret()?');

        $connection =  (new Connection($client, $requestFactory, $streamFactory))
            ->setBaseUri($baseUri)
            ->setApiKey($apiKey)
            ->setApiSecret($apiSecret);

        return new Client($connection);
    }
}