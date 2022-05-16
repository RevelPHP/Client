<?php

namespace RevelPHP\QueryBuilders;

use RevelPHP\Connection;
use RevelPHP\Support\Page;

final class PageQueryBuilder
{
    private array $parameters = [];

    public function __construct(private Connection $connection, private string $endpoint)
    {}

    public function orderBy(string $field): self
    {
        return $this->parameter('order_by', $field);
    }

    public function fields(string ...$fields): self
    {
        return $this->parameter('fields', $fields);
    }

    public function filter(string $field, string $operator, mixed $value): self
    {
        $key = strtolower($field).'__'.strtolower($operator);

        return $this->parameter($key, $value);
    }

    public function limit(int $limit): self
    {
        return $this->parameter('limit', $limit);
    }

    public function offset(int $offset): self
    {
        return $this->parameter('offset', $offset);
    }

    public function parameter(string $name, mixed $value): self
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $this->parameters[$name] = $value;

        return $this;
    }

    public function get(): Page
    {
        return new Page(
            $this->connection, $this->connection->get($this->endpoint, $this->parameters)
        );
    }
}