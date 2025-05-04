<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\{Email, FlusherInterface, Id, UserRepositoryInterface};
use App\Auth\Service\{NewEmailConfirmTokenSender, Tokenizer};
use DateTimeImmutable;

final readonly class Handler
{
    /**
     * Handle the command.
     *
     * @param UserRepositoryInterface $users
     * @param Tokenizer $tokenizer
     * @param NewEmailConfirmTokenSender $sender
     * @param FlusherInterface $flasher
     */
    public function __construct(
        private UserRepositoryInterface    $users,
        private Tokenizer                  $tokenizer,
        private NewEmailConfirmTokenSender $sender,
        private FlusherInterface $flasher
    ) {
    }

    /**
     * Запрос на смену Email.
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {

        $user = $this->users->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('This email is already in use.');
        }

        $date = new DateTimeImmutable();

        $user->requestEmailChanging(
            $token = $this->tokenizer->generate($date),
            $date,
            $email
        );

        $this->flasher->flush();

        $this->sender->send(
            $email,
            $token
        );
    }
}
