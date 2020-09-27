<?php

declare(strict_types=1);

namespace Dictionary\Application\UpdateMeaning;

final class UpdateMeaningCommand
{
    private string $meaningId;
    private string $translation;
    private string $definition;
    private array $examples;

    public function __construct(string $meaningId, string $translation, string $definition, string ...$examples)
    {
        $this->meaningId = $meaningId;
        $this->translation = $translation;
        $this->definition = $definition;
        $this->examples = $examples;
    }

    public function getMeaningId(): string
    {
        return $this->meaningId;
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
