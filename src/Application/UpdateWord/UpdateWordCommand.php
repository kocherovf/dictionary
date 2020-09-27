<?php

declare(strict_types=1);

namespace Dictionary\Application\UpdateWord;

final class UpdateWordCommand
{
    private string $text;
    private string $wordId;

    public function __construct(string $wordId, string $text)
    {
        $this->wordId = $wordId;
        $this->text = $text;
    }

    public function getWordId(): string
    {
        return $this->wordId;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
