<?php

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;

class NewEmailConfirmTokenSender implements JoinConfirmationSender
{
    public function send(Email $email, Token $token): void
    {
        // TODO: Implement send() method.
    }
}
