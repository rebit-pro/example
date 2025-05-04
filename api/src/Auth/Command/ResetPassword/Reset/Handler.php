<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\FlusherInterface;
use App\Auth\Entity\User\UserRepositoryInterface;
use App\Auth\Service\PasswordHasher;
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
        private FlusherInterface        $flasher,
        private PasswordHasher          $hasher,
    ) {
    }

    /**
     * Сброс пароля.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByPasswordResetToken($command->token)) {
            throw new \DomainException('Token is not found.');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flasher->flush();
    }
}
