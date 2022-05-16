<?php

namespace RevelPHP\QueryBuilders;

use Illuminate\Support\Collection;
use RevelPHP\Connection;
use RevelPHP\Support\Response;

final class SingleEntityQueryBuilder
{
    private array $parameters = [];

    public function __construct(private Connection $connection, private string $endpoint)
    {}

    public function fields(string ...$fields): self
    {
        return $this->parameter('fields', $fields);
    }

    public function parameter(string $name, mixed $value): self
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $this->parameters[$name] = $value;

        return $this;
    }

    public function get(): Collection
    {
        $response = $this->connection->get($this->endpoint, $this->parameters);

        return Response::collection($response);
    }
}