<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

final class JsonResponse extends Response
{
    private function __construct($data, int $statusCode = 200, array $headers = [])
    {
        parent::__construct(
            $statusCode,
            new Headers(array_merge(['Content-Type' => 'application/json'], $headers)),
            (new StreamFactory())->createStream(json_encode($data, JSON_THROW_ON_ERROR))
        );
    }

    public static function create($data, int $statusCode = 200, array $headers = [])
    {
        return new self($data, $statusCode, $headers);
    }

    public static function emtpy(int $statusCode = 204, array $headers = [])
    {
        return new self(null, $statusCode, $headers);
    }
}
