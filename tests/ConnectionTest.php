<?php

namespace RevelPHP\Tests;

use AidanCasey\MockClient\Client;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use RevelPHP\Connection;
use RevelPHP\Tests\Factories\ConnectionFactory;

class ConnectionTest extends TestCase
{
    public function test_it_requires_base_uri()
    {
        $this->expectExceptionObject(
            new Exception('No base uri was set. Did you forget to call setBaseUri()?')
        );

        ConnectionFactory::new()->make()->getBaseUri();
    }

    public function test_it_requires_api_key()
    {
        $this->expectExceptionObject(
            new Exception('No api key was set. Did you forget to call setApiKey()?')
        );

        ConnectionFactory::new()->make()->getApiKey();
    }

    public function test_it_requires_api_secret()
    {
        $this->expectExceptionObject(
            new Exception('No api secret was set. Did you forget to call setApiSecret()?')
        );

        ConnectionFactory::new()->make()->getApiSecret();
    }

    public function test_it_sets_base_uri()
    {
        $connection = ConnectionFactory::new()->make();

        $connection->setBaseUri('https://test.revelup.com/');

        $this->assertSame('https://test.revelup.com', $connection->getBaseUri());
    }

    public function test_it_sets_api_authentication()
    {
        $connection = ConnectionFactory::new()->make();

        $connection->setApiKey('ABC123');
        $connection->setApiSecret('XYZ123');

        $this->assertSame('ABC123', $connection->getApiKey());
        $this->assertSame('XYZ123', $connection->getApiSecret());
    }

    public function test_it_sends_get_request()
    {
        $client = new Client;
        $connection = $this->getConnection($client);

        $connection->get('resources/Customers');

        $client
            ->assertMethod('GET')
            ->assertUri('https://test.revelup.com/resources/customers/');
    }

    public function test_it_sends_get_request_with_query_parameters()
    {
        $client = new Client;
        $connection = $this->getConnection($client);

        $connection->get('resources/Customers', [
            'fields' => 'id,name',
        ]);

        $client
            ->assertMethod('GET')
            ->assertUri('https://test.revelup.com/resources/Customers/?fields=id%2cname');
    }

    public function test_it_sends_post_request()
    {
        $client = new Client;
        $connection = $this->getConnection($client);

        $connection->post('resources/Customers', '{"name":"Test User"}');

        $client
            ->assertMethod('POST')
            ->assertUri('https://test.revelup.com/resources/customers/')
            ->assertBodyIs('{"name":"Test User"}');
    }

    public function test_it_adds_authentication_headers_to_requests()
    {
        $client = new Client;
        $connection = $this->getConnection($client);

        $connection->send('GET', 'resources/Customers');

        $client
            ->assertHeaderEquals('API-AUTHENTICATION', 'ABC123:XYZ123')
            ->assertHeaderEquals('content-type', 'application/json');
    }

    private function getConnection(?ClientInterface $client = null): Connection
    {
        $client = $client ?? new Client;

        return ConnectionFactory::new()
            ->client($client)
            ->baseUri('https://test.revelup.com')
            ->apiKey('ABC123')
            ->apiSecret('XYZ123')
            ->make();
    }
}