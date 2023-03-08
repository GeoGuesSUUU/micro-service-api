<?php

namespace App\Controller;

use App\Entity\AuthDTO;
use App\Entity\User;
use App\Entity\Command;
use App\Exception\CommandNotFoundApiException;
use App\Exception\UserNotFoundApiException;
use App\Service\CommandService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use OpenApi\Attributes as OAA;

#[OAA\Tag(name: 'Auth & User')]
#[Security(name: 'Bearer')]
class UserController extends AbstractController
{

    /**
     * Login API
     * @OA\RequestBody(@Model(type=User::class, groups={"user:login"}))
     * @OA\Response(
     *     response=200,
     *     description="Login Form API",
     *     @Model(type=AuthDTO::class, groups={"auth", "user"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="User is not valid"
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserService $userService
     * @return Response
     */
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
            ['groups' => ['auth', 'user']]
        );
    }

    /**
     * Valid User Command By ID (Send Email !)
     * @OA\Response(
     *     response=200,
     *     description="Valid User Command",
     *     @Model(type=Command::class, groups={"command", "command:products" , "product", "user", "store" , "slot"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="User is not valid"
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @param int $userId
     * @param int $commandId
     * @param CommandService $commandService
     * @param UserService $userService
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/api/users/{userId}/commands/{commandId}/validate', name: 'app_user_valid_command', methods: ['POST'], format: 'application/json')]
    public function validCommand(
        int $userId,
        int $commandId,
        CommandService $commandService,
        UserService $userService,
    ): Response
    {
        $user = $userService->getById($userId);
        if (is_null($user)) throw new UserNotFoundApiException();

        $command = $commandService->get($commandId);
        if (is_null($command)) throw new CommandNotFoundApiException();

        $command = $commandService->validCommand($command, $user, true);
        return $this->json(ApiResponse::get($command),
            200,
            [],
            ['groups' => ['command', 'command:products', 'product', 'user', 'store', 'slot']]
        );
    }
}
