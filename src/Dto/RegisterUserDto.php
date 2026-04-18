<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], message: 'Пользователь с таким email уже существует', entityClass: User::class)]
final class RegisterUserDto
{
    #[Assert\NotBlank(message: 'Поле email не может быть пустым.')]
    #[Assert\Email(message: 'Неверный формат email.')]
    public string $email;

    #[Assert\NotBlank(message: 'Пароль не может быть пустым')]
    #[Assert\Length(min: 6, minMessage: 'Пароль должен содержать не менее {{ limit }} символов.')]
    public string $password;
}
