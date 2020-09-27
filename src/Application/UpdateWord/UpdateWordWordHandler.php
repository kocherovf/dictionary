<?php

declare(strict_types=1);

namespace Dictionary\Application\UpdateWord;

use Dictionary\Domain\Model\Word\Text;
use Dictionary\Domain\Model\Word\WordId;
use Dictionary\Domain\Repository\WordRepositoryInterface;

final class UpdateWordWordHandler
{
    private WordRepositoryInterface $wordRepository;

    public function __construct(WordRepositoryInterface $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function handle(UpdateWordCommand $command): void
    {
        $word = $this->wordRepository->getById(new WordId($command->getWordId()));

        $word->setText(new Text($command->getText()));

        $this->wordRepository->save($word);
    }
}
