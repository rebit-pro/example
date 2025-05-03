<?php

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider as ProviderInterface;

return [
    \Doctrine\ORM\EntityManagerInterface::class => function (\DI\Container $container) {
        $settings = $container->get('config')['doctrine'];

        // Убедитесь, что cache_dir - строка
        $cacheDir = is_array($settings['cache_dir'])
            ? __DIR__ . '/../../var/cache/doctrine/cache' // Fallback
            : $settings['cache_dir'];

        $config = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $cacheDir !== null
                ? new FilesystemAdapter('', 0, $cacheDir)
                : new \Symfony\Component\Cache\Adapter\ArrayAdapter()
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());
        $connection = \Doctrine\DBAL\DriverManager::getConnection($settings['connection'], $config);
        return new \Doctrine\ORM\EntityManager($connection, $config);
    },
    'config' => [
        'doctrine' => [
            'dev_mode' => false,
            'metadata_dirs' => [],
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
        ],
    ],
    // говорим контейнеру, как сделать EntityManagerProvider
    ProviderInterface::class => function (ContainerInterface $container) {
        return new SingleManagerProvider(
            $container->get(EntityManagerInterface::class)
        );
    },
];