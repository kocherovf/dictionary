<?php

declare(strict_types=1);

namespace Dictionary\Domain;

final class UUID
{
    public static function create(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
