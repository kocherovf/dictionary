<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Meaning;

use Dictionary\Infrasturcture\Api\Http\AbstractValidator;
use Symfony\Component\Validator\Constraint;

final class Validator extends AbstractValidator
{
    protected function buildConstraint(): Constraint
    {
        return $this->isCollectionOf(
            [
                'translation' => array_merge(
                    $this->isNotBlankString(),
                    $this->onlyCyrillic(),
                    $this->maxLength(150),
                ),
                'definition' => array_merge(
                    $this->isNotBlankString(),
                    $this->onlyLatin(),
                    $this->maxLength(150),
                ),
                'examples' => $this->isCollectionOf(
                    array_merge(
                        $this->isNotBlankString(),
                        $this->onlyLatin(),
                        $this->maxLength(150),
                    )
                ),
            ]
        );
    }
}
