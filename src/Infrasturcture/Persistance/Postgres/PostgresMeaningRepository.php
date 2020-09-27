<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Persistance\Postgres;

use Dictionary\Domain\Model\Meaning\Definition;
use Dictionary\Domain\Model\Meaning\Example;
use Dictionary\Domain\Model\Meaning\Meaning;
use Dictionary\Domain\Model\Meaning\MeaningId;
use Dictionary\Domain\Model\Meaning\Translation;
use Dictionary\Domain\Model\Word\WordId;
use Dictionary\Domain\Repository\MeaningRepositoryInterface;
use Dictionary\Domain\Repository\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

final class PostgresMeaningRepository implements MeaningRepositoryInterface
{
    private const TABLE = 'meaning';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->$connection = $connection;
    }

    public function nextIdentity(): MeaningId
    {
        return new MeaningId();
    }

    /**
     * @inheritDoc
     */
    public function getById(MeaningId $id): Meaning
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $row = $queryBuilder
            ->select('id, translation, definition, examples')
            ->from(self::TABLE)
            ->where('id = :id')
            ->setParameter(':id', $id->getValue(), ParameterType::STRING)
            ->execute()
            ->fetchOne();

        if ($row === null) {
            throw new NotFoundException(sprintf('Meaning with id %s is not found', $id->getValue()));
        }

        return $this->mapMeaning($row);
    }

    private function mapMeaning(array $row): Meaning
    {
        $examples = json_decode($row['examples'], true, 512, JSON_THROW_ON_ERROR);

        return new Meaning(
            new WordId($row['wordId']),
            new PersistenceMeaningId($row['id']),
            new Translation($row['translation']),
            new Definition($row['definition']),
            array_map(fn(string $example) => new Example($example), $examples)
        );
    }

    /**
     * @inheritDoc
     */
    public function getByWordId(WordId $id): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $rows = $queryBuilder
            ->select('id, translation, definition, examples')
            ->from(self::TABLE)
            ->where('word_id = :word_id')
            ->setParameter(':word_id', $id->getValue(), ParameterType::STRING)
            ->execute()
            ->fetchAllNumeric();

        return array_map([$this, 'mapMeaning'], $rows);
    }

    public function save(Meaning $meaning): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        if ($meaning->getId() instanceof PersistenceMeaningId) {
            $queryBuilder->update(self::TABLE);
        } else {
            $queryBuilder->insert(self::TABLE);
        }

        $queryBuilder
            ->values(
                [
                    'id' => ':id',
                    'translation' => ':translation',
                    'definition' => ':definition',
                    'examples' => ':examples',
                    'updated_at' => 'now()',
                ]
            )
            ->setParameter(':id', $meaning->getId()->getValue(), ParameterType::STRING)
            ->setParameter(':translation', $meaning->getTranslation()->getValue(), ParameterType::STRING)
            ->setParameter(':definition', $meaning->getDefinition()->getValue(), ParameterType::STRING)
            ->setParameter(':examples', json_encode($meaning->getExamples()), ParameterType::STRING)
            ->execute();
    }
}
