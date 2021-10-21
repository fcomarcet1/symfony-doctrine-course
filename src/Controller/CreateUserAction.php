<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CreateUserService;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CreateUserAction extends AbstractController
{

    /* private CreateUserService $createUserService;
    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
    } */

    public function __construct(private CreateUserService $createUserService)
    {
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException|\Doctrine\ORM\ORMException
     */
    public function __invoke(Request $request): JsonResponse
    {
        // get data from Request

        try {
            $inputData = json_decode(
                $request->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Exception $e) {
            return new JsonResponse([
                "message" => 'User created successful',
                "status" => 'error',
                "code" => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }


        // call service
        $user = ($this->createUserService)($inputData['name'], $inputData['email']);
        //$user = $this->createUserService->__invoke($inputData['name'], $inputData['email']);

        return new JsonResponse([
            "message" => 'User created successful',
            "status" => 'success',
            "code" => JsonResponse::HTTP_CREATED,
            "data" => [
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'createdAt' => $user->getCreatedAt()->format(DateTime::RFC3339),
                ],
            ],
        ], JsonResponse::HTTP_CREATED);
    }


    /*  #[Route('/create/user/action', name: 'create_user_action')]
     public function index(): Response
     {
         return $this->json([
             'message' => 'Welcome to your new controller!',
             'path' => 'src/Controller/CreateUserActionController.php',
         ]);
     } */
}
