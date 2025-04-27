<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\User\Flusher;
use App\Auth\Entity\User\UserRepository;
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepository $users
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
        if ($user = $this->users->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token.');
        }

        $user->confirmEmailChanging(
            $command->token,
            new DateTimeImmutable()
        );

        $this->flasher->flush();
    }
}
