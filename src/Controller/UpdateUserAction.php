<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UpdateUserService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;

class UpdateUserAction extends AbstractController
{

    public function __construct(private UpdateUserService $updateUserService)
    {
    }


    public function __invoke(Request $request, string $id): JsonResponse
    {
        // get data from Request
        $inputData = json_decode($request->getContent(), true);
        //dump($inputData); die();

        // call service
        $user = ($this->updateUserService)($id, $inputData['name']);

        return new JsonResponse([
            "message" => 'User updated successful',
            "status" => 'success',
            "code" => JsonResponse::HTTP_OK,
            "data" => [
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'createdAt' => $user->getCreatedAt()->format(DateTime::RFC3339),
                ],
            ],
        ], JsonResponse::HTTP_OK);
    }

}
