<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\Flusher;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
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
        private Flusher $flasher,
        private PasswordHasher $hasher,
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
