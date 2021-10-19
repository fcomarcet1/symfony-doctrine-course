<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class CreateUserService
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $name, string $email): User
    {
        $user = new User($name, $email);
        $this->userRepository->save($user);

        return $user;
    }

}