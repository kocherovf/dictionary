<?php

declare(strict_types=1);

namespace Dictionary\Application\CreateWord;

final class CreateWordCommand
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
