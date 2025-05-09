<?php

declare(strict_types=1);

namespace App\Auth\tests\Builder;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use Ramsey\Uuid\Nonstandard\Uuid;

class UserBuilder
{
    private Id $id;
    private \DateTimeImmutable $date;
    private Email $email;
    private string $hash;
    private Token $joinConfirmToken;
    private bool $active = false;
    private ?Network $networkIdentity = null;
    public function __construct()
    {
        $this->id = Id::generate();
        $this->date = new \DateTimeImmutable();
        $this->email = new Email('mail@example.com');
        $this->hash = 'hash';
        $this->joinConfirmToken = new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable());
    }

    public function active(): self
    {
        $clone = clone $this;
        $clone->active = true;

        return $clone;
    }

    public function viaNetwork(Network $identity = null): self
    {
        $clone = clone $this;
        $clone->networkIdentity = $identity ?? new Network('google', 'google-1');

        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    public function withJoinConfirmToken(Token $token): self
    {
        $clone = clone $this;
        $clone->joinConfirmToken = $token;

        return $clone;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function build(): User
    {

        if ($this->networkIdentity) {
            return User::joinByNetwork(
                $this->id,
                $this->date,
                $this->email,
                $this->networkIdentity,
            );
        }

        $user = User::requestJoinByEmail(
            $this->id,
            $this->date,
            $this->email,
            $this->hash,
            $this->joinConfirmToken,
        );

        if ($this->active) {
            $user->confirmJoin(
                $this->joinConfirmToken->getValue(),
                $this->joinConfirmToken->getExpires()->modify('-1 day'),
            );
        }

        return $user;
    }
}
