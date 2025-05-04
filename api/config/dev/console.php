<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

return [
    'config' => [
        'console' => [
            'commands' => [
                DropCommand::class,
                CreateCommand::class,
                UpdateCommand::class,
                InfoCommand::class,
                ValidateSchemaCommand::class
            ],
        ],
    ],
];