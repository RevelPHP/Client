<?php

namespace RevelPHP\Reports;

interface ReportInterface
{
    public static function new(): self;

    public function getReportEndpoint(): string;

    public function getReportParameters(): array;
}