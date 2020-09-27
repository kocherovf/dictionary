<?php

declare(strict_types=1);


namespace Dictionary\Domain\Model\Meaning;

use Dictionary\Domain\DomainException;
use Dictionary\Domain\Model\Word\WordId;

final class Meaning
{
    private const MAX_EXAMPLES_COUNT = 10;

    private MeaningId $id;
    private Translation $translation;
    private Definition $definition;
    private array $examples;
    private WordId $wordId;

    public function __construct(
        WordId $wordId,
        MeaningId $id,
        Translation $translation,
        Definition $definition,
        Example ...$examples
    ) {
        $this->id = $id;
        $this->wordId = $wordId;
        $this->setTranslation($translation);
        $this->setDefinition($definition);
        $this->setExamples($examples);
    }

    public function getId(): MeaningId
    {
        return $this->id;
    }

    public function getWordId(): WordId
    {
        return $this->wordId;
    }

    public function getTranslation(): Translation
    {
        return $this->translation;
    }

    public function setTranslation(Translation $translation): void
    {
        $this->translation = $translation;
    }

    public function getDefinition(): Definition
    {
        return $this->definition;
    }

    public function setDefinition(Definition $definition): void
    {
        $this->definition = $definition;
    }

    public function getExamples(): array
    {
        return $this->examples;
    }

    public function setExamples(Example ...$examples): void
    {
        if (count($examples) > self::MAX_EXAMPLES_COUNT) {
            throw new DomainException('Amount of examples must not exceed ' . self::MAX_EXAMPLES_COUNT);
        }
        $this->examples = $examples;
    }
}
