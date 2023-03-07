<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\BadRequestApiException;
use App\Exception\MessageNotFoundApiException;
use App\Exception\ProductNotFoundApiException;
use App\Exception\SlotAlreadyBookedApiException;
use App\Exception\SlotNotFoundApiException;
use App\Exception\StoreNotFoundApiException;
use App\Exception\UserNotFoundApiException;
use App\Exception\UserNotSellerApiException;
use App\Service\CommandService;
use App\Service\MessageService;
use App\Service\ProductService;
use App\Service\SlotService;
use App\Service\StoreService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/sellers')]
class SellerController extends AbstractController
{
    #[Route('/{sellerId}/messages/send', name: 'app_seller_message_send', methods: ['POST'], format: 'application/json')]
    public function send(
        int $sellerId,
        UserService $userService,
        MessageService $messageService,
        Request $request
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $seller = $userService->getById($sellerId);
        if (is_null($seller ?? null)) throw new UserNotFoundApiException();
        if ($userService->isSeller($user)) throw new UserNotSellerApiException();

        $content = json_decode($request->getContent(), true);
        if (is_null($content['message'] ?? null)) throw new BadRequestApiException();

        $messageService->send($user, $seller, $content['message'], true);
        return $this->json(ApiResponse::get(''),
            200,
            [],
            ['groups' => []]
        );
    }

    #[Route('/messages', name: 'app_seller_message_all', methods: ['GET'], format: 'application/json')]
    public function seeAll(
        UserService $userService,
        MessageService $messageService
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($userService->isSeller($user)) throw new UserNotSellerApiException();

        $messages = $messageService->getAllByDestUser($user);

        return $this->json(ApiResponse::get($messages),
            200,
            [],
            ['groups' => ['message', 'user']]
        );
    }

    #[Route('/messages/{messageId}', name: 'app_seller_message_one', methods: ['GET'], format: 'application/json')]
    public function seeOne(
        int $messageId,
        UserService $userService,
        MessageService $messageService
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($userService->isSeller($user)) throw new UserNotSellerApiException();

        $message = $messageService->getOneByDestUser($user, $messageId);
        if (is_null($message ?? null)) throw new MessageNotFoundApiException();

        return $this->json(ApiResponse::get($message),
            200,
            [],
            ['groups' => ['message', 'user']]
        );
    }

}
