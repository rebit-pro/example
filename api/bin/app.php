<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;


require __DIR__ . "/../vendor/autoload.php";

/** @var ContainerInterface $container */
$container = require __DIR__ . "/../config/container.php";

/*
 * @var string[] $commands
 * @psalm-suppress MixedArrayAccess
 */
$commands = $container->get('config')['console']['commands'];

$cli = new Application('cli');
$cli->setCatchExceptions(true);

foreach ($commands as $name) {
    /**  @var Command $command */
    $command = $container->get($name);
    $cli->add($command);

}

$cli->run();