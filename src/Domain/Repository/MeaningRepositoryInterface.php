<?php

declare(strict_types=1);

namespace Dictionary\Domain\Repository;

use Dictionary\Domain\Model\Meaning\Meaning;
use Dictionary\Domain\Model\Meaning\MeaningId;
use Dictionary\Domain\Model\Word\WordId;

interface MeaningRepositoryInterface
{
    public function nextIdentity(): MeaningId;

    /**
     * @throws NotFoundException
     */
    public function getById(MeaningId $id): Meaning;

    /**=
     * @return array<Meaning>
     */
    public function getByWordId(WordId $id): array;

    public function save(Meaning $meaning): void;
}
