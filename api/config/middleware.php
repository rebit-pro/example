<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface as Container;
use Slim\App;

/**
 * @param App<ContainerInterface> $app
 * @param ContainerInterface $container
 */
return static function (App $app, Container $container): void {
    /** @psalm-var array{debug:bool, env: string} */
    $config = $container->get('config');

    $app->addErrorMiddleware($config['debug'], $config['env'] !== 'test', true);
};