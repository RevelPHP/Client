<?php

namespace RevelPHP;

use Illuminate\Support\Collection;
use RevelPHP\Reports\ReportInterface;
use RevelPHP\Services\CustomerService;
use RevelPHP\Services\EstablishmentService;
use RevelPHP\Support\Response;

final class Client
{
    public readonly CustomerService $customers;
    public readonly EstablishmentService $establishments;

    public function __construct(private readonly Connection $connection)
    {
        $this->customers = new CustomerService($this->connection);
        $this->establishments = new EstablishmentService($this->connection);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function report(ReportInterface $report): Collection
    {
        $response = $this->connection->get(
            endpoint: $report->getReportEndpoint(),
            query: $report->getReportParameters()
        );

        return Response::collection($response);
    }
}