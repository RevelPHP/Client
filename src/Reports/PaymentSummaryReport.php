<?php

namespace RevelPHP\Reports;

use DateTimeInterface;
use DateTimeZone;
use Exception;

final class PaymentSummaryReport implements ReportInterface
{
    private array $parameters = [];

    public static function new(): ReportInterface
    {
        return new self;
    }

    public function getReportEndpoint(): string
    {
        return '/reports/payment_summary/json/';
    }

    public function getReportParameters(): array
    {
        $this->assertRequiredFieldsAreSet();

        return $this->parameters;
    }

    public function employee(string $employee): self
    {
        $this->parameters['employee'] = $employee;

        return $this;
    }

    public function establishment(int $establishmentId): self
    {
        $this->parameters['establishment'] = $establishmentId;

        return $this;
    }

    public function posStation(string $posStation): self
    {
        $this->parameters['posstation'] = $posStation;

        return $this;
    }

    public function rangeFrom(DateTimeInterface $dateTime): self
    {
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $this->parameters['range_from'] = $dateTime->format('Y-m-d H:i:s');

        return $this;
    }

    public function rangeTo(DateTimeInterface $dateTime): self
    {
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $this->parameters['range_to'] = $dateTime->format('Y-m-d H:i:s');

        return $this;
    }

    public function showIrregular(bool $boolean = true): self
    {
        $this->parameters['show_irregular'] = ($boolean) ? 1 : 0;

        return $this;
    }

    public function showUnpaid(bool $boolean = true): self
    {
        $this->parameters['show_unpaid'] = ($boolean) ? 1 : 0;

        return $this;
    }

    private function assertRequiredFieldsAreSet(): void
    {
        if (! isset($this->parameters['range_from'])) {
            throw new Exception('The range from is required. Did you forget to call rangeFrom()?');
        }

        if (! isset($this->parameters['range_to'])) {
            throw new Exception('The range to is required. Did you forget to call rangeTo()?');
        }

        if (! isset($this->parameters['establishment'])) {
            throw new Exception('The establishment is required. Did you forget to call establishment()?');
        }
    }
}