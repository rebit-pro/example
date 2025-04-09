<?php

declare(strict_types=1);

use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\{ResponseFactoryInterface};

return [
    'config' => [
        'env' => getenv('APP_ENV') ?: 'prod',
        'debug' => (bool) getenv('APP_DEBUG')
    ],
    ResponseFactoryInterface::class => DI\get(ResponseFactory::class)
];