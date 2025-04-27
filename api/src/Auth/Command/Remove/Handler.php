<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Auth\Entity\User\{Flusher, Id, UserRepository};

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
     * Удаление пользователя.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->remove();

        $this->users->remove($user);

        $this->flasher->flush();
    }
}
