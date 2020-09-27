<?php

declare(strict_types=1);

namespace Dictionary\Domain\Model\Meaning;

use Dictionary\Domain\DomainException;

final class Definition
{
    private const MAX_LENGTH = 150;

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    private function validate(string $value): void
    {
        $length = mb_strlen($value);
        if ($length === 0) {
            throw new DomainException('Definition must not be empty');
        }
        if ($length > self::MAX_LENGTH) {
            throw new DomainException(sprintf('Definition must not be longer that %d', self::MAX_LENGTH));
        }
        if (preg_match('/^[\w\s]+$/', $value) !== 1) {
            throw new DomainException('Definition must contain only latin letters, digits and spaces');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
