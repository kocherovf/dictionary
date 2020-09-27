<?php

declare(strict_types=1);

namespace Dictionary\Domain\Model\Meaning;

use Dictionary\Domain\UUID;

class MeaningId
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
