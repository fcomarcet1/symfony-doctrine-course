<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

//use Doctrine\ORM\ORMException;

abstract class DoctrineBaseRepository
{
    /* private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry  = $managerRegistry;
        $this->connection       = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    } */

    protected ObjectRepository $objectRepository;

    public function __construct(
        private ManagerRegistry $managerRegistry,
        protected Connection    $connection
    )
    {
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }


    protected function getEntityManager(): EntityManager|ObjectManager
    {
        $entityManager = $this->managerRegistry->getManager();
        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }

    /**
     * @return ObjectManager|EntityManager
     */
    /* public function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    } */
    abstract protected static function entityClass(): string;

    /**
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    protected function persistEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException|\Doctrine\ORM\ORMException
     */
    protected function flushData(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException|\Doctrine\ORM\ORMException
     */
    protected function saveEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException|\Doctrine\ORM\ORMException
     */
    protected function removeEntity(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException|\Doctrine\ORM\ORMException
     */
    protected function refreshEntity(object $entity): void
    {
        $this->getEntityManager()->refresh($entity);
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    protected function executeFetchQuery(string $query, array $params = []): array
    {
        return $this->connection->executeQuery($query, $params)->fetchAll();
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    protected function executeQuery(string $query, array $params = []): void
    {
        $this->connection->executeQuery($query, $params);
    }


}