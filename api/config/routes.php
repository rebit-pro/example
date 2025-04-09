<?php

declare(strict_types=1);

use App\Http;
use Slim\App;

/**
 * @param App<ContainerInterface> $app
 */
return static function (App $app): void {
    $app->get("/", Http\Action\HomeAction::class);
};