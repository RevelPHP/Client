<?php

namespace RevelPHP\Services;

use Illuminate\Support\Collection;
use RevelPHP\QueryBuilders\PageQueryBuilder;
use RevelPHP\QueryBuilders\SingleEntityQueryBuilder;
use RevelPHP\Connection;
use RevelPHP\Support\Page;
use RevelPHP\Support\Response;

final class CustomerService
{
    public function __construct(private readonly Connection $connection)
    {}

    public function get(): Page
    {
        return $this->query()->get();
    }

    public function getById(int $customerId): Collection
    {
        return $this->queryById($customerId)->get();
    }

    public function query(): PageQueryBuilder
    {
        return new PageQueryBuilder($this->connection, 'resources/Customer');
    }

    public function queryById(int $customerId): SingleEntityQueryBuilder
    {
        return new SingleEntityQueryBuilder($this->connection, "resources/Customer/{$customerId}");
    }
}