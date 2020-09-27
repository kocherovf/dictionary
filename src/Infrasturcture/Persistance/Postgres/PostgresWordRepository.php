<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Persistance\Postgres;

use Dictionary\Domain\Model\Word\Text;
use Dictionary\Domain\Model\Word\Word;
use Dictionary\Domain\Model\Word\WordId;
use Dictionary\Domain\Repository\NotFoundException;
use Dictionary\Domain\Repository\WordRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final class PostgresWordRepository implements WordRepositoryInterface
{
    private const TABLE = 'word';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->$connection = $connection;
    }

    public function nextIdentity(): WordId
    {
        return new WordId();
    }

    /**
     * @inheritDoc
     */
    public function getById(WordId $id): Word
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $row = $queryBuilder
            ->select('id, text')
            ->from(self::TABLE)
            ->where('id = :id')
            ->setParameter(':id', $id->getValue(), ParameterType::STRING)
            ->execute()
            ->fetchOne();

        if ($row === null) {
            throw new NotFoundException(sprintf('Word with id %s is not found', $id->getValue()));
        }

        return new Word(
            new PersistenceWordId($row['id']),
            new Text($row['text'])
        );
    }

    public function save(Word $word): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        if ($word->getId() instanceof PersistenceWordId) {
            $queryBuilder->update(self::TABLE);
        } else {
            $queryBuilder->insert(self::TABLE);
        }

        $queryBuilder
            ->values(
                [
                    'id' => ':id',
                    'text' => ':text',
                    'updated_at' => 'now()',
                ]
            )
            ->setParameters(
                [
                    ':id' => $word->getId()->getValue(),
                    ':text' => $word->getText()->getValue(),
                ]
            )
            ->execute();
    }
}
