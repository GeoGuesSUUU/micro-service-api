<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\User;
use App\Exception\CommandNotFoundApiException;
use App\Exception\UserNotFoundApiException;
use App\Exception\UserNotValidApiException;
use App\Repository\UserRepository;
use App\Service\CommandService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserApiController extends AbstractController
{

    #[Route('/api/auth/login', name: 'app_user_api_login', methods: ['POST'], format: 'application/json')]
    public function login(
        Request                     $request,
        SerializerInterface         $serializer,
        UserService                 $userService
    ): Response
    {
        /** @var User $body */
        $body = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => []]
        );

        $response = $userService->auth($body);

        return $this->json(ApiResponse::get($response),
            200,
            [],
            ['groups' => ['user']]
        );
    }

    #[Route('/api/users/{userId}/command/{commandId}', name: 'app_user_valid_command', methods: ['POST'], format: 'application/json')]
    public function validCommand(
        int $userId,
        int $commandId,
        CommandService $commandService,
        UserService $userService,
    ): Response
    {
        $user = $userService->get($userId);
        if (is_null($user)) throw new UserNotFoundApiException();

        $command = $commandService->get($commandId);
        if (is_null($command)) throw new CommandNotFoundApiException();

        $command = $commandService->validCommand($command, true);
        return $this->json(ApiResponse::get($command),
            200,
            [],
            ['groups' => ['command', 'command:products', 'product']]
        );
    }
}
