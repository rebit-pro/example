<?php

use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

return [
    \Doctrine\ORM\EntityManagerInterface::class => function (\DI\Container $container) {

        $settings = $container->get('config')['doctrine'];

        $config = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $settings['cache_dir'] !== null ?
                new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, $settings['cache_dir']) :
                new \Symfony\Component\Cache\Adapter\ArrayAdapter()
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
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
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
    ]
];
