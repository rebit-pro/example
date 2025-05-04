<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\{Email, FlusherInterface, Id, User, UserRepositoryInterface};
use App\Auth\Service\{JoinConfirmationSender, PasswordHasher, Tokenizer};
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositoryInterface $usersRepository
     * @param PasswordHasher $hasher
     * @param Tokenizer $tokenizer
     * @param JoinConfirmationSender $sender
     * @param FlusherInterface $flasher
     */
    public function __construct(
        private UserRepositoryInterface $usersRepository,
        private PasswordHasher          $hasher,
        private Tokenizer               $tokenizer,
        private JoinConfirmationSender  $sender,
        private FlusherInterface $flasher
    ) {
    }

    /**
     * Join by email.
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

        $user = User::requestJoinByEmail(
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
