<?php

declare(strict_types=1);

namespace Dictionary\Application\AddMeaningToWord;

use Dictionary\Domain\Model\Meaning\Definition;
use Dictionary\Domain\Model\Meaning\Example;
use Dictionary\Domain\Model\Meaning\Translation;
use Dictionary\Domain\Model\Word\WordId;
use Dictionary\Domain\Repository\MeaningRepositoryInterface;
use Dictionary\Domain\Repository\WordRepositoryInterface;

final class AddMeaningToWordHandler
{
    private WordRepositoryInterface $wordRepository;
    private MeaningRepositoryInterface $meaningRepository;

    public function __construct(WordRepositoryInterface $wordRepository, MeaningRepositoryInterface $meaningRepository)
    {
        $this->wordRepository = $wordRepository;
        $this->meaningRepository = $meaningRepository;
    }

    public function handle(AddMeaningToWordCommand $command): string
    {
        $word = $this->wordRepository->getById(new WordId($command->getWordId()));

        $meaning = $word->makeMeaning(
            $this->meaningRepository->nextIdentity(),
            new Translation($command->getTranslation()),
            new Definition($command->getDefinition()),
            array_map(static fn(string $example) => new Example($example), $command->getExamples())
        );

        $this->meaningRepository->save($meaning);

        return $meaning->getId()->getValue();
    }
}
