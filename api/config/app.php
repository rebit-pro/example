<?php

declare(strict_types=1);

use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $container): App {

    $app = AppFactory::createFromContainer($container);

    /** @var App<ContainerInterface|null> $appForConfig */
    $appForConfig = $app;

    /** @psalm-suppress InvalidArgument */
    (require __DIR__ . '/../config/middleware.php')($appForConfig, $container);

    /** @psalm-suppress InvalidArgument */
    (require __DIR__ . '/../config/routes.php')($appForConfig);

    return $app;
};


