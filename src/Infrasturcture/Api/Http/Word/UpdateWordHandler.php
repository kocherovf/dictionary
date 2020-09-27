<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Word;

use Dictionary\Application\UpdateWord\UpdateWordCommand;
use Dictionary\Domain\Repository\NotFoundException;
use Dictionary\Infrasturcture\Api\Http\JsonResponse;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateWordHandler
{
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
            $this->commandBus->handle($command);
        } catch (NotFoundException $exception) {
            return JsonResponse::create(['errors' => [$exception->getMessage()]], 404);
        }

        return JsonResponse::emtpy();
    }

    private function createCommand(string $wordId, array $body): UpdateWordCommand
    {
        return new UpdateWordCommand(
            $wordId,
            $body['text']
        );
    }
}
