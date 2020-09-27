<?php

declare(strict_types=1);

namespace Dictionary\Infrasturcture\Api\Http\Word;

use Dictionary\Application\CreateWord\CreateWordCommand;
use Dictionary\Infrasturcture\Api\Http\JsonResponse;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateWordHandler
{
    private const LOCATION_TEMPLATE = '/words/%s';

    private CommandBus $commandBus;
    private Validator $validator;

    public function __construct(CommandBus $commandBus, Validator $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            return JsonResponse::create(['errors' => $errors], 400);
        }

        $command = $this->createCommand($request->getParsedBody());

        $wordId = $this->commandBus->handle($command);

        return JsonResponse::create(
            ['id' => $wordId],
            201,
            ['Location' => sprintf(self::LOCATION_TEMPLATE, $wordId)]
        );
    }

    private function createCommand(array $body): CreateWordCommand
    {
        return new CreateWordCommand(
            $body['text']
        );
    }
}
