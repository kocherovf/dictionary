<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Meaning;

use Dictionary\Application\UpdateMeaning\UpdateMeaningCommand;
use Dictionary\Domain\Repository\NotFoundException;
use Dictionary\Infrasturcture\Api\Http\JsonResponse;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateMeaningHandler
{
    private CommandBus $commandBus;
    private Validator $validator;

    public function __construct(CommandBus $commandBus, Validator $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request, string $meaningId): ResponseInterface
    {
        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            return JsonResponse::create(['errors' => $errors], 400);
        }

        $command = $this->createCommand($meaningId, $request->getParsedBody());

        try {
            $this->commandBus->handle($command);
        } catch (NotFoundException $exception) {
            return JsonResponse::create(['errors' => [$exception->getMessage()]], 404);
        }

        return JsonResponse::emtpy();
    }

    private function createCommand(string $meaningId, array $body): UpdateMeaningCommand
    {
        return new UpdateMeaningCommand(
            $meaningId,
            $body['translation'],
            $body['definition'],
            ...$body['examples']
        );
    }
}
