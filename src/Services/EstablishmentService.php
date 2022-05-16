<?php

namespace RevelPHP\Services;

use Illuminate\Support\Collection;
use RevelPHP\QueryBuilders\PageQueryBuilder;
use RevelPHP\QueryBuilders\SingleEntityQueryBuilder;
use RevelPHP\Connection;
use RevelPHP\Support\Page;

final class EstablishmentService
{
    public function __construct(private Connection $connection)
    {}

    public function get(): Page
    {
        return $this->query()->get();
    }

    public function getById(int $establishmentId): Collection
    {
        return $this->queryById($establishmentId)->get();
    }

    public function query(): PageQueryBuilder
    {
        return new PageQueryBuilder($this->connection, 'enterprise/Establishment');
    }

    public function queryById(int $establishmentId): SingleEntityQueryBuilder
    {
        return new SingleEntityQueryBuilder($this->connection, "enterprise/Establishment/{$establishmentId}");
    }
}