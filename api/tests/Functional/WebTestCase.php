<?php

declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Psr\Container\ContainerInterface;

class WebTestCase extends TestCase
{
    protected static function json(string $method, string $path): ServerRequestInterface
    {
        return self::request($method, $path)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json');
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())
            ->createServerRequest($method, $path);
    }

    protected function app(): App
    {
        /** @var App $app */
        return (require __DIR__ . '/../../config/app.php')($this->container());
    }

    private function container(): ContainerInterface
    {
        /** @var ContainerInterface $container */
        return require __DIR__ . "/../../config/container.php";
    }
}
