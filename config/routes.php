<?php
declare(strict_types=1);

use Dictionary\Infrasturcture\Api\Http\Meaning\AddMeaningToWordHandler;
use Dictionary\Infrasturcture\Api\Http\Meaning\UpdateMeaningHandler;
use Dictionary\Infrasturcture\Api\Http\Word\CreateWordHandler;
use Dictionary\Infrasturcture\Api\Http\Word\UpdateWordHandler;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->group('/words', static function (RouteCollectorProxy $group) {
        $group->post('', CreateWordHandler::class);
        $group->put('/{wordId}', UpdateWordHandler::class);
        $group->post('/{wordId}/meanings', AddMeaningToWordHandler::class);
    });
    $app->put(
        '/meanings/{meaningId}',
        UpdateMeaningHandler::class
    );
};
