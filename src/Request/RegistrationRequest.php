<?php

namespace App\Request;

use App\Entity\User;
use App\Validator\UniqueByEntityWithField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationRequest
{
    #[NotBlank]
    #[Length(
        min: 4,
        max: 16,
    )]
    #[UniqueByEntityWithField(
        User::class,
        'username',
    )]
    public string $username;

    #[NotCompromisedPassword]
    public string $password;
}