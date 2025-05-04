<?php

declare(strict_types=1);

use App\Auth\Entity\User\Flasher;
use App\Auth\Entity\User\FlusherInterface;
use DI\{ContainerBuilder};
use App\Auth\Entity\User\UserRepository;
use App\Auth\Entity\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

$builder = new ContainerBuilder();

$builder->addDefinitions(require __DIR__ . '/dependencies.php');

$builder->addDefinitions([
    UserRepositoryInterface::class => function (EntityManagerInterface $em) {
        return new UserRepository($em);
    },
    FlusherInterface::class => function (EntityManagerInterface $em) {
        return new Flasher($em);
    },
]);

try {
    return $container = $builder->build();
} catch (Exception $e) {
    throw new Exception($e->getMessage());
}