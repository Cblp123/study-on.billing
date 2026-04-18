<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], message: 'пользователь с таким email уже существует', entityClass: User::class)]
final class RegisterUserDto
{
    #[Assert\NotBlank(message: 'вапррп.')]
    #[Assert\Email(message: 'Invalid email address.')]
    public string $email;

    #[Assert\NotBlank(message: 'Password should not be blank.')]
    #[Assert\Length(min: 6, minMessage: 'Password should be at least {{ limit }} characters long.')]
    public string $password;
}
