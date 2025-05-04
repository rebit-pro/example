<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\User\{FlusherInterface, Id, Network, UserRepositoryInterface};
use App\Auth\Service\{JoinConfirmationSender, PasswordHasher, Tokenizer};

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositoryInterface $users
     * @param FlusherInterface $flasher
     */
    public function __construct(
        private UserRepositoryInterface $users,
        private FlusherInterface $flasher
    ) {
    }

    /**
     * Attach network identity to user.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $identity = new Network($command->network, $command->identity);

        if ($this->users->hasByNetwork($identity)) {
            throw new \DomainException('User with this network already exists');
        }

        $user = $this->users->get(new Id($command->id));

        $user->attachNetwork($identity);

        $this->flasher->flush();
    }
}
