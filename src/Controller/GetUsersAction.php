<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GetUsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUsersAction extends AbstractController
{

    public function __construct(private GetUsersService $getUsersService)
    {
    }

    public function __invoke(): JsonResponse
    {
        $users = ($this->getUsersService)();
        //$users = $this->getUserService->__invoke();  
        
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getName(),
                'created_at' => $user->getCreatedAt(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);

        /* return new JsonResponse([
                "message" => 'Users list',
                "status" => 'success',
                "code" => JsonResponse::HTTP_OK,
                "data" => $users, // return [{},{},{},{}]
            ], 
            JsonResponse::HTTP_OK
        ); */
    }

}
