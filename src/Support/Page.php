<?php

namespace RevelPHP\Support;

use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use RevelPHP\Connection;

class Page
{
    public readonly Collection $items;

    private ?string $nextPage = null;

    private ?string $previousPage = null;

    public function __construct(private readonly Connection $connection, ResponseInterface $response)
    {
        $this->setResponse($response);
    }

    public function hasPreviousPage(): bool
    {
        return $this->previousPage !== null;
    }

    public function previousPage(): Page
    {
        // TODO: Validation

        return new self(
            $this->connection, $this->connection->get($this->previousPage)
        );
    }

    public function hasNextPage(): bool
    {
        return $this->nextPage !== null;
    }

    public function nextPage(): self
    {
        // TODO: Validation

        return new self(
            $this->connection, $this->connection->get($this->nextPage)
        );
    }

    private function setResponse(ResponseInterface $response): void
    {
        $response = json_decode($response->getBody(), true);
        //TODO: Validation
        $this->nextPage = $response['meta']['next'] ?? null;
        $this->previousPage = $response['meta']['previous'] ?? null;
        $this->items = collect($response['objects']);
    }
}