<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\UserRepositary;
use DateTimeImmutable;

final class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositary $usersRepository
     */
    public function __construct(
        private readonly UserRepository $users,
        private readonly Flusher $flasher
    ) {
    }

    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        if (!$this->users->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token.');
        }

        $user->confirmJoin($command->token, new DateTimeImmutable());

        $this->flasher->flush();
    }
}
