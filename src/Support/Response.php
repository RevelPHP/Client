<?php

namespace RevelPHP\Support;

use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

final class Response
{
    public static function collection(ResponseInterface $response): Collection
    {
        $response = json_decode($response->getBody(), true);

        return collect($response);
    }
}