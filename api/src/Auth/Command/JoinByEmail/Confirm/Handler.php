<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\FlusherInterface;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Entity\User\UserRepositoryInterface;
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
     * Join by email confirmation.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        if ($user = $this->users->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token.');
        }

        $user->confirmJoin(
            $command->token,
            new DateTimeImmutable()
        );

        $this->flasher->flush();
    }
}
