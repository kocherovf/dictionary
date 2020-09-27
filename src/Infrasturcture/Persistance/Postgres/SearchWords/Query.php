<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Persistance\Postgres\SearchWords;

final class Query
{
    private string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

}
