<?php

declare(strict_types=1);


namespace Dictionary\Infrasturcture\Api\Http;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(ServerRequestInterface $request): array
    {
        $constraint = $this->buildConstraint();

        $violations = $this->validator->validate($request->getParsedBody(), $constraint);
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            if (!array_key_exists($violation->getPropertyPath(), $errors)) {
                $errors[$violation->getPropertyPath()] = [];
            }
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        return $errors;
    }

    abstract protected function buildConstraint(): Constraint;

    protected function isCollectionOf(array $fields): Collection
    {
        return new Collection(
            [
                'fields' => $fields,
                'allowExtraFields' => true,
            ]
        );
    }

    protected function isNotBlankNumeric(): array
    {
        return [
            new Assert\Type('numeric'),
            new Assert\NotBlank(),
        ];
    }

    protected function isNotBlankString(): array
    {
        return [
            new Assert\Type('string'),
            new Assert\NotBlank(),
        ];
    }

    protected function isNotNullBool(): array
    {
        return [
            new Assert\Type('boolean'),
            new Assert\NotNull(),
        ];
    }

    protected function isNotBlankArray(): array
    {
        return [
            new Assert\Type('array'),
            new Assert\NotBlank(),
        ];
    }

    protected function isNotNullInteger(): array
    {
        return [
            new Assert\Type('integer'),
            new Assert\NotNull(),
        ];
    }

    protected function matchRegexp(string $pattern, string $message): array
    {
        return [
            new Assert\Regex(
                [
                    'pattern' => $pattern,
                    'message' => $message
                ]
            ),
        ];
    }

    protected function onlyLatin(): array
    {
        return [
            $this->matchRegexp('/^[\w\s]+$/', 'Field must contain only latin letters, digits and spaces'),
        ];
    }

    protected function onlyCyrillic(): array
    {
        return [
            $this->matchRegexp('/^[а-яА-Я\s\d]+$/u', 'Field must contain only cyrillic letters, digits and spaces'),
        ];
    }

    protected function maxLength(int $maxLength): array
    {
        return [
            new Assert\Length(['min' => 0, 'max' => $maxLength]),
        ];
    }
}
