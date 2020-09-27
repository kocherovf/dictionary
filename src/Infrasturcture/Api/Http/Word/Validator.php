<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Word;

use Dictionary\Infrasturcture\Api\Http\AbstractValidator;
use Symfony\Component\Validator\Constraint;

final class Validator extends AbstractValidator
{
    protected function buildConstraint(): Constraint
    {
        return $this->isCollectionOf(
            [
                'text' => array_merge(
                    $this->isNotBlankString(),
                    $this->maxLength(100),
                    $this->onlyLatin()
                ),
            ]
        );
    }
}
