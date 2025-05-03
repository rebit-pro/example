<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;

return [
    'config' => [
        'console' => [
            'commands' => [
                DropCommand::class,
                CreateCommand::class,
                UpdateCommand::class,
            ],
        ],
    ],
];