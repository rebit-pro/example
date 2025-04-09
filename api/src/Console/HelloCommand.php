<?php

declare(strict_types=1);

namespace App\Console;

use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class HelloCommand extends Command
{
    #[Override]
    protected function configure(): void
    {
        $this
            ->setName('hell')
            ->setDescription('Hello command');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Hello</info>');

        return 0;
    }
}
