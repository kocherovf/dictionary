<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Persistance\Postgres\SearchWords;

use Doctrine\DBAL\Connection;

final class Provider
{
    private const WORD_TABLE = 'word';
    private const MEANING_TABLE = 'meaning';

    private const LIMIT = 50;

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function search(Query $query): Result
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $rows = $queryBuilder
            ->select('w.id as wordId, w.text, m.id as meaningId, m.translation')
            ->from(self::WORD_TABLE, 'w')
            ->innerJoin('w', self::MEANING_TABLE, 'm', 'w.id = m.wordId')
            ->where('w.text LIKE "*:query*"')
            ->orWhere('m.translation LIKE "*:query*"')
            ->setParameter(':query', $query->getQuery())
            ->setMaxResults(self::LIMIT)
            ->execute()
            ->fetchNumeric();

        $entries = array_map(
            static fn(array $row) => new Entry(
                $row['wordId'],
                $row['text'],
                $row['meaningId'],
                $row['translation'],
            ),
            $rows
        );

        return new Result(...$entries);
    }
}
