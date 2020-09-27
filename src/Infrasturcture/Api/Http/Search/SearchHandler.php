<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Search;

use Dictionary\Infrasturcture\Api\Http\JsonResponse;
use Dictionary\Infrasturcture\Persistance\Postgres\SearchWords\Provider;
use Dictionary\Infrasturcture\Persistance\Postgres\SearchWords\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SearchHandler
{
    private Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        if (!array_key_exists('query', $queryParams)) {
            return JsonResponse::create(['errors' => ['Parameter query is mandatory']], 400);
        }

        $result = $this->provider->search(new Query($queryParams['query']));

        return JsonResponse::create(
            $result->toArray(),
            200,
        );
    }

}
