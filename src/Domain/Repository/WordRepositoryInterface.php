<?php

declare(strict_types=1);

namespace Dictionary\Domain\Repository;

use Dictionary\Domain\Model\Word\Word;
use Dictionary\Domain\Model\Word\WordId;

interface WordRepositoryInterface
{
    public function nextIdentity(): WordId;

    /**
     * @throws NotFoundException
     */
    public function getById(WordId $id): Word;

    public function save(Word $word): void;
}
