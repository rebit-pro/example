<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\User\{Flusher, Id, NetworkIdentity, UserRepository};
use App\Auth\Service\{JoinConfirmationSender, PasswordHasher, Tokenizer};

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepository $usersRepository
     * @param PasswordHasher $hasher
     * @param Tokenizer $tokenizer
     * @param JoinConfirmationSender $sender
     * @param Flusher $flasher
     */
    public function __construct(
        private UserRepository $users,
        private Flusher $flasher
    ) {
    }

    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $identity = new NetworkIdentity($command->network, $command->identity);

        if ($this->users->hasByNetwork($identity)) {
            throw new \DomainException('User with this network already exists');
        }

        $user = $this->users->get(new Id($command->id));

        $user->attachNetwork($identity);

        $this->flasher->flush();
    }
}
