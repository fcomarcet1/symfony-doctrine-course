<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\HttpKernel\Exception\HttpException;


class DoctrineUserRepository extends DoctrineBaseRepository
{

    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneById(string $id): ?User
    {
        if (null === $user = $this->objectRepository->find($id)) {
            throw new HttpException(404, 'Something was wrong');
        }
        return $user;
    }

    public function findOneByEmail(string $email): ?User
    {
        if (null === $user = $this->objectRepository->find($email)) {
            throw new HttpException(404, 'Something was wrong');
        }
        return $user;
    }

    public function findAll(): array
    {
        if (null === $users = $this->objectRepository->findAll()) {
            dump($users);
        }

        return $users;
    }

    /**
     * @throws NonUniqueResultException
     */
    // Son mas complejas las querys pero nos permiten añadir logica
    public function findOneByIdWithQueryBuilder(string $id): ?User
    {
        $qb = $this->objectRepository->createQueryBuilder('u');
        $query = $qb
            // no necesita un select ya que con el alias 'u' de $qb sabe que tabla es
            ->where(
                $qb->expr()->eq('u.id', ':id')
            )
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    // Son mas complejas las querys pero nos permiten añadir logica
    /*public function findOneByIdWithQueryBuilder2(string $id): ?User
    {
        $qb = $this->objectRepository->createQueryBuilder('u');
        $query = $qb
            // no necesita un select ya que con el alias 'u' de $qb sabe que tabla es
            ->where(
                $qb->expr()->eq('u.id', ':id')
            )
            ->setParameter('id', $id);
        if ($id = '875834') {
            $qb->andWhere('u.is_active =1')
            }


        return $query->getOneOrNullResult();
    }*/


    /**
     * @throws NonUniqueResultException
     */
    public function findOneByIdWithDQL(string $id): ?User
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\User u WHERE u.id = :id'
        );
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOnyByIdWithNativeQuery(string $id): ?User
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(User::class, 'u');

        $query = $this->getEntityManager()->createNativeQuery('SELECT * FROM user WHERE id = :id', $rsm);
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws Exception
     */
    public function findOneByIdWithPlainSQL(string $id): array
    {
        $params = [
            ':id' => $this->getEntityManager()->getConnection()->quote($id),
        ];
        $query = 'SELECT * FROM user WHERE id = :id';

        return $this->getEntityManager()->getConnection()->executeQuery(strtr($query, $params))->fetchAllAssociative();
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException|\Doctrine\ORM\ORMException
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