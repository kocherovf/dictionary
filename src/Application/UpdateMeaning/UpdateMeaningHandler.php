<?php

declare(strict_types=1);

namespace Dictionary\Application\UpdateMeaning;

use Dictionary\Domain\Model\Meaning\Definition;
use Dictionary\Domain\Model\Meaning\Example;
use Dictionary\Domain\Model\Meaning\MeaningId;
use Dictionary\Domain\Model\Meaning\Translation;
use Dictionary\Domain\Repository\MeaningRepositoryInterface;

final class UpdateMeaningHandler
{
    private MeaningRepositoryInterface $meaningRepository;

    public function __construct(MeaningRepositoryInterface $meaningRepository)
    {
        $this->meaningRepository = $meaningRepository;
    }

    public function handle(UpdateMeaningCommand $command): void
    {
        $meaning = $this->meaningRepository->getById(new MeaningId($command->getMeaningId()));

        $meaning->setTranslation(new Translation($command->getTranslation()));
        $meaning->setDefinition(new Definition($command->getDefinition()));
        $meaning->setExamples(
            ...array_map(static fn(string $example) => new Example($example), $command->getExamples())
        );

        $this->meaningRepository->save($meaning);
    }
}
