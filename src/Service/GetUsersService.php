<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\DoctrineUserRepository;


class GetUsersService
{

    public function __construct(private DoctrineUserRepository $doctrineUserRepository)
    {
    }

    public function __invoke(): array
    {
        return $this->doctrineUserRepository->findAll();
    }

}
