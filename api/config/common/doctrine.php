<?php

use App\Auth\Entity\User\EmailType;
use App\Auth\Entity\User\IdType;
use App\Auth\Entity\User\RoleType;
use App\Auth\Entity\User\StatusType;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider as ProviderInterface;

return [
    EntityManagerInterface::class => function (Container $container) {
        $settings = $container->get('config')['doctrine'];

        $cacheDir = is_array($settings['cache_dir'])
            ? __DIR__ . '/../../var/cache/doctrine/cache'
            : $settings['cache_dir'];

        $config = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $cacheDir !== null
                ? new FilesystemAdapter('', 0, $cacheDir)
                : new ArrayAdapter()
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        foreach ($settings['types'] as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        $connection = DriverManager::getConnection($settings['connection'], $config);
        return new EntityManager($connection, $config);
    },
    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache', // Должно быть строкой
            'connection' => [
                'driver' => 'pdo_pgsql',
                'charset' => 'utf-8',
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT'),
                'dbname' => getenv('DB_NAME'),
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
            ],
            'metadata_dirs' => [
                __DIR__ . '/../../src/Auth/Entity',
            ],
            'types' => [
                IdType::NAME => IdType::class,
                EmailType::NAME => EmailType::class,
                RoleType::NAME => RoleType::class,
                StatusType::NAME => StatusType::class,
            ],
        ],
    ],
    // говорим контейнеру, как сделать EntityManagerProvider
    ProviderInterface::class => function (ContainerInterface $container) {
        return new SingleManagerProvider(
            $container->get(EntityManagerInterface::class)
        );
    },
];