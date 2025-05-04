<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\{Email, FlusherInterface, Id, Network, User, UserRepositoryInterface};
use DateTimeImmutable;

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
     * Join by network.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $identity = new Network($command->network, $command->identity);
        $email = new Email($command->email);

        if ($this->users->hasByNetwork($identity)) {
            throw new \DomainException('User with this network already exists');
        }

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User with this email already exists');
        }

        $user = User::joinByNetwork(
            Id::generate(),
            new DateTimeImmutable(),
            $email,
            $identity
        );

        $this->users->add($user);

        $this->flasher->flush();
    }
}
