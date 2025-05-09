<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\{FlusherInterface, Id, UserRepositoryInterface};
use App\Auth\Service\{PasswordHasher};
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositoryInterface $users
     * @param PasswordHasher $hash
     * @param FlusherInterface $flasher
     */
    public function __construct(
        private UserRepositoryInterface $users,
        private PasswordHasher          $hash,
        private FlusherInterface $flasher
    ) {
    }

    /**
     * Запрашивает смену пароля.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->changePassword(
            $command->new,
            $command->current,
            $this->hash
        );

        $this->flasher->flush();
    }
}
