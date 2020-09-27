<?php

declare(strict_types=1);

namespace Dictionary\Domain\Model\Word;

use Dictionary\Domain\UUID;

class WordId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        if ($value === null) {
            $this->value = UUID::create();
        } else {
            $this->value = $value;
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
