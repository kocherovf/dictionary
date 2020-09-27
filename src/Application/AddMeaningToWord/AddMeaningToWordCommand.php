<?php

declare(strict_types=1);

namespace Dictionary\Application\AddMeaningToWord;

final class AddMeaningToWordCommand
{
    private string $wordId;
    private string $translation;
    private string $definition;
    private array $examples;

    public function __construct(string $wordId, string $translation, string $definition, string ...$examples)
    {
        $this->wordId = $wordId;
        $this->translation = $translation;
        $this->definition = $definition;
        $this->examples = $examples;
    }

    public function getWordId(): string
    {
        return $this->wordId;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    public function getExamples(): array
    {
        return $this->examples;
    }
}
