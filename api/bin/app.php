<?php

declare(strict_types=1);


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;



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

// 2) Добавляем Doctrine ORM‑команды через новый провайдер
/** @var EntityManagerInterface $em */
$em = $container->get(EntityManagerInterface::class);
$connection = $em->getConnection();

$ormProvider = new EntityManagerProvider\SingleManagerProvider($em);
Doctrine\Migrations\Tools\Console\ConsoleRunner::addCommands($cli);

foreach ($commands as $name) {
    /**  @var Command $command */
    $command = $container->get($name);
    $cli->add($command);

}

$cli->run();