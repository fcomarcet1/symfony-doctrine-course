<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\Repository\DoctrineBaseRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class DoctrineUserRepository extends DoctrineBaseRepository
{

    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneById(string $id): ?User
    {
        if (null === $user = $this->objectRepository->find($id)) {
            throw new HttpException(404, 'Somethig was wrong');
        }
        return $user;
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function refresh(User $user): void
    {
        $this->refreshEntity($user);
    }

    /*  public function findOneByIdOrFail(string $id): User
    {
        if (null === $user = $this->objectRepository->find($id)) {
            throw UserNotFoundException::fromUserId($id);
        }

        return $user;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    public function findOneInactiveByIdAndTokenOrFail(string $id, string $token): User
    {
        if (null === $user = $this->objectRepository->findOneBy([
                'id'     => $id,
                'token'  => $token,
                'active' => false,
            ])) {
            throw UserNotFoundException::fromUserIdAndToken($id, $token);
        }

        return $user;
    }

    public function findOneByIdAndResetPasswordToken(string $id, string $resetPasswordToken): User
    {
        if (null === $user = $this->objectRepository->findOneBy([
                'id'                 => $id,
                'resetPasswordToken' => $resetPasswordToken,
            ])) {
            throw UserNotFoundException::fromUserIdAndResetPasswordToken($id, $resetPasswordToken);
        }

        return $user;

    } */


}