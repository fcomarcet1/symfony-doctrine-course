<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\DoctrineUserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;


class UpdateUserService
{

    public function __construct(private DoctrineUserRepository $userRepository)
    {
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    public function __invoke(string $id, string $name): User
    {
        if (null !== $user = $this->userRepository->findOneById($id)) {
            $user->setName($name);
            $this->userRepository->save($user);
        }
        return $user;
    }

}
