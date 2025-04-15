<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use DateTimeImmutable;
use Ramsey\Uuid\Nonstandard\Uuid;
use Webmozart\Assert\Assert;

final class Handler
{

    /**
     * Handle the command.
     *
     * @param UserRepository $usersRepository
     */
    public function __construct(
        private readonly UserRepository $usersRepository,
        private readonly PasswordHasher $hasher,
        private readonly Tokenizer $tokenizer,
        private readonly JoinConfirmationSender $sender,
        private readonly Flasher $flasher
    ) {}

    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command)
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