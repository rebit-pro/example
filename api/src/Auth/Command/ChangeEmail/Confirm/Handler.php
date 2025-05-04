<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\User\FlusherInterface;
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
     * Смена email.
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
