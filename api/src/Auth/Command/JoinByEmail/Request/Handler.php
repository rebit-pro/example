<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepositary;
use App\Auth\Service\JoinConfirmationSender;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\Tokenizer;
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositary $usersRepository
     */
    public function __construct(
        private UserRepositary         $usersRepository,
        private PasswordHasher         $hasher,
        private Tokenizer              $tokenizer,
        private JoinConfirmationSender $sender,
        private Flasher                $flasher
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
