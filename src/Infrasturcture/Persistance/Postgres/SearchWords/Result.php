<?php

declare(strict_types=1);


namespace Dictionary\Infrasturcture\Persistance\Postgres\SearchWords;

final class Result
{
    private array $entries;

    public function __construct(Entry ...$words)
    {
        $this->entries = $words;
    }

    public function toArray(): array
    {
        return array_map(static fn(Entry $entry) => $entry->toArray(), $this->entries);
    }
}
