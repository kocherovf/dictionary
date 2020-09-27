<?php

declare(strict_types=1);

namespace Dictionary\Application\CreateWord;

use Dictionary\Domain\Model\Word\Text;
use Dictionary\Domain\Model\Word\Word;
use Dictionary\Domain\Repository\WordRepositoryInterface;

final class CreateWordHandler
{
    private WordRepositoryInterface $wordRepository;

    public function __construct(WordRepositoryInterface $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function handle(CreateWordCommand $command): string
    {
        $word = new Word(
            $this->wordRepository->nextIdentity(),
            new Text($command->getText())
        );

        $this->wordRepository->save($word);

        return $word->getId()->getValue();
    }
}
