<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Meaning;

use Dictionary\Application\AddMeaningToWord\AddMeaningToWordCommand;
use Dictionary\Domain\Repository\NotFoundException;
use Dictionary\Infrasturcture\Api\Http\JsonResponse;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AddMeaningToWordHandler
{
    private const LOCATION_TEMPLATE = '/meaning/%s';

    private CommandBus $commandBus;
    private Validator $validator;

    public function __construct(CommandBus $commandBus, Validator $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request, string $wordId): ResponseInterface
    {
        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            return JsonResponse::create(['errors' => $errors], 400);
        }

        $command = $this->createCommand($wordId, $request->getParsedBody());

        try {
            $meaningId = $this->commandBus->handle($command);
        } catch (NotFoundException $exception) {
            return JsonResponse::create(['errors' => [$exception->getMessage()]], 404);
        }

        return JsonResponse::create(
            ['id' => $meaningId],
            201,
            ['Location' => sprintf(self::LOCATION_TEMPLATE, $meaningId)]
        );
    }

    private function createCommand(string $wordId, array $body): AddMeaningToWordCommand
    {
        return new AddMeaningToWordCommand(
            $wordId,
            $body['translation'],
            $body['definition'],
            ...$body['examples']
        );
    }
}
