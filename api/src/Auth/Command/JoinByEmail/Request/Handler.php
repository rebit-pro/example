<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\{Email, Flusher, Id, User, UserRepository};
use App\Auth\Service\{JoinConfirmationSender, PasswordHasher, Tokenizer};
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepository $usersRepository
     * @param PasswordHasher $hasher
     * @param Tokenizer $tokenizer
     * @param JoinConfirmationSender $sender
     * @param Flusher $flasher
     */
    public function __construct(
        private UserRepository $usersRepository,
        private PasswordHasher $hasher,
        private Tokenizer $tokenizer,
        private JoinConfirmationSender $sender,
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
        $now = new DateTimeImmutable();

        if ($this->usersRepository->hasByEmail($email)) {
            throw new \DomainException('User already exists');
        }

        $user = new User(
            Id::generate(),
            $now,
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate($now)
        );

        $this->usersRepository->add($user);

        $this->flasher->flush();

        $this->sender->send($email, $token);
    }
}
