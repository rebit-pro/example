<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\{Email, Flusher, Id, User, UserRepository};
use App\Auth\Service\{JoinConfirmationSender, PasswordHasher, PasswordResetTokenSender, Tokenizer};
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepository $users
     * @param Tokenizer $tokenizer
     * @param PasswordResetTokenSender $sender
     * @param Flusher $flasher
     */
    public function __construct(
        private UserRepository $users,
        private Tokenizer $tokenizer,
        private PasswordResetTokenSender $sender,
        private Flusher $flasher
    ) {
    }

    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {

        $email = new Email($command->email);

        $user = $this->users->getByEmail($email);

        $date = new DateTimeImmutable();

        $user->requestPasswordReset(
            $token = $this->tokenizer->generate($date),
            $date
        );

        $this->flasher->flush();

        $this->sender->send($email, $token);
    }
}
