<?php

namespace RevelPHP\Tests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RevelPHP\ClientFactory;

class ClientFactoryTest extends TestCase
{
    public function test_it_requires_base_uri()
    {
        $this->expectExceptionObject(
            new Exception('No base uri was set. Did you forget to call baseUri()?')
        );

        ClientFactory::new()->make();
    }

    public function test_it_requires_api_key()
    {
        $this->expectExceptionObject(
            new Exception('No api key was set. Did you forget to call apiKey()?')
        );

        ClientFactory::new()
            ->baseUri('https://test.revelup.com')
            ->make();
    }

    public function test_it_requires_api_secret()
    {
        $this->expectExceptionObject(
            new Exception('No api secret was set. Did you forget to call apiSecret()?')
        );

        ClientFactory::new()
            ->baseUri('https://test.revelup.com')
            ->apiKey('ABC123')
            ->make();
    }

    public function test_it_finds_psr_interfaces_if_not_set()
    {
        $client = ClientFactory::new()
            ->baseUri('https://test.revelup.com')
            ->apiKey('ABC123')
            ->apiSecret('XYZ123')
            ->make();

        $this->assertInstanceOf(ClientInterface::class, $client->getConnection()->getHttpClient());
        $this->assertInstanceOf(RequestFactoryInterface::class, $client->getConnection()->getRequestFactory());
        $this->assertInstanceOf(StreamFactoryInterface::class, $client->getConnection()->getStreamFactory());
    }

    public function test_it_uses_client_if_set()
    {
        $httpClient = new Client();
        $client = ClientFactory::new()
            ->baseUri('https://test.revelup.com')
            ->apiKey('ABC123')
            ->apiSecret('XYZ123')
            ->client($httpClient)
            ->make();

        $this->assertSame($httpClient, $client->getConnection()->getHttpClient());
    }

    public function test_it_uses_factories_if_set()
    {
        $factory = new HttpFactory();
        $client = ClientFactory::new()
            ->baseUri('https://test.revelup.com')
            ->apiKey('ABC123')
            ->apiSecret('XYZ123')
            ->requestFactory($factory)
            ->streamFactory($factory)
            ->make();

        $this->assertSame($factory, $client->getConnection()->getRequestFactory());
        $this->assertSame($factory, $client->getConnection()->getStreamFactory());
    }
}