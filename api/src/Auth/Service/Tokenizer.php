<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Token;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Nonstandard\Uuid;

final class Tokenizer
{
    public function __construct(
        private DateInterval $interval,
    ) {}

    /**
     * @param DateTimeImmutable $date
     * @return Token
     */
    public function generate(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date->add($this->interval)
        );
    }
}