<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use AllowDynamicProperties;
use App\Auth\Service\PasswordHasher;

#[AllowDynamicProperties] final class User
{
    private ?string $passwordHash = null;
    private ?Token $joinConfirmToken = null;
    private ?Token $passwordResetToken = null;
    private ?Email $newEmail = null;
    private ?Token $newEmailToken = null;
    private \ArrayObject $networks;
    private Role $role;

    private function __construct(
        private readonly Id $id,
        private \DateTimeImmutable $date,
        private Email $email,
        private Status $status
    ) {
        $this->networks = new \ArrayObject();
        $this->role = Role::user();
    }

    public static function requestJoinByEmail(
        Id $id,
        \DateTimeImmutable $date,
        Email $email,
        string $hash,
        Token $joinConfirmToken
    ): self {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $hash;
        $user->joinConfirmToken = $joinConfirmToken;
        return $user;
    }

    public static function joinByNetwork(
        Id $id,
        \DateTimeImmutable $date,
        Email $email,
        NetworkIdentity $identity
    ): self {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($identity);
        return $user;
    }

    public function attachNetwork(NetworkIdentity $identity): void
    {
        /** @var NetworkIdentity $network */
        foreach ($this->networks as $network) {
            if ($network->isEqualTo($identity)) {
                throw new \DomainException('User with this network already exists.');
            }
        }

        $this->networks->append($identity);
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getNetworks(): array
    {
        return $this->networks->getArrayCopy();
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function confirmJoin(string $token, \DateTimeImmutable $date): void
    {
        if ($this->isActive() === true) {
            throw new \DomainException('User is already active.');
        }

        if ($this->joinConfirmToken === null) {
            throw new \DomainException('Confirmation is not required.');
        }

        $this->joinConfirmToken->validate($token, $date);

        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function confirmEmailChanging(string $token, \DateTimeImmutable $date): void
    {
        if ($this->newEmailToken === null || $this->newEmail === null) {
            throw new \DomainException('Email is not requested.');
        }

        $this->newEmailToken->validate($token, $date);

        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function requestPasswordReset(Token $token, \DateTimeImmutable $date): void
    {
        if ($this->isActive() === false) {
            throw new \DomainException('User is not active.');
        }

        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new \DomainException('Password reset token already exists.');
        }

        $token->validate($token->getValue(), $date);

        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, string $hash, \DateTimeImmutable $date): void
    {
        if ($this->isActive() === false) {
            throw new \DomainException('User is not active.');
        }

        if ($this->passwordResetToken === null) {
            throw new \DomainException('Password reset token is not requested.');
        }

        $this->passwordResetToken->validate($token, $date);

        $this->passwordHash = $hash;
        $this->passwordResetToken = null;
    }

    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->isActive() === false) {
            throw new \DomainException('User is not active.');
        }

        if ($this->passwordHash === null) {
            throw new \DomainException('User does not have an old password.');
        }

        if (!$hasher->validate($current, $this->passwordHash)) {
            throw new \DomainException('Wrong current password');
        }

        $this->passwordHash = $hasher->hash($new);
    }

    public function requestEmailChanging(
        Token $token,
        \DateTimeImmutable $date,
        Email $email,
    ): void {
        if ($this->isActive() === false) {
            throw new \DomainException('User is not active.');
        }

        if ($this->email->isEqualTo($email)) {
            throw new \DomainException('New email is the same as current.');
        }

        if ($this->newEmailToken !== null && !$this->newEmailToken->isExpiredTo($date)) {
            throw new \DomainException('Email is already requested.');
        }

        $token->validate($token->getValue(), $date);

        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqualTo($role)) {
            throw new \DomainException('Role is already same.');
        }

        $this->role = $role;
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('Unable to remove active user.');
        }
    }
}
