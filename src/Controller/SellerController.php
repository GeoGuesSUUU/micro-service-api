<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageInput;
use App\Entity\User;
use App\Exception\BadRequestApiException;
use App\Exception\MessageNotFoundApiException;
use App\Exception\SendMessageFieldRequiredApiException;
use App\Exception\UserNotFoundApiException;
use App\Exception\UserNotSellerApiException;
use App\Service\MessageService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use OpenApi\Attributes as OAA;

#[OAA\Tag(name: 'Seller')]
#[Security(name: 'Bearer')]
#[Route('/api/sellers')]
class SellerController extends AbstractController
{
    /**
     * Send Message to a Seller
     * @OA\RequestBody(@Model(type=MessageInput::class, groups={"message:input"}))
     * @OA\Response(
     *     response=204,
     *     description="Send Message to a selected seller"
     * )
     * @OA\Response(
     *     response=400,
     *     description="User is not a seller"
     * )
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @param int $sellerId
     * @param UserService $userService
     * @param MessageService $messageService
     * @param Request $request
     * @return Response
     */
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
        if ($userService->isSeller($seller)) throw new UserNotSellerApiException();

        $content = json_decode($request->getContent(), true);
        if (is_null($content['message'] ?? null)) throw new BadRequestApiException();
        if (is_null($user ?? null) && is_null($content['author'] ?? null)) throw new SendMessageFieldRequiredApiException();

        $message = is_null($content['author'] ?? null) ? $content['message'] : $content['author'] . ' : ' . $content['message'] ;

        $messageService->send($user, $seller, $message, true);
        return $this->json(ApiResponse::get(null, 204),
            204,
            [],
            ['groups' => []]
        );
    }

    /**
     * Get all Messages (Only Seller)
     * @OA\Parameter(name="page", in="query")
     * @OA\Parameter(name="limit", in="query")
     * @OA\Response(
     *     response=200,
     *     description="Return all your messages if you're seller",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Message::class, groups={"message", "user"}))
     *     )
     * )
     * @param UserService $userService
     * @param MessageService $messageService
     * @param Request $request
     * @return Response
     */
    #[Route('/messages', name: 'app_seller_message_all', methods: ['GET'], format: 'application/json')]
    public function seeAll(
        UserService $userService,
        MessageService $messageService,
        Request $request
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($userService->isSeller($user)) throw new UserNotSellerApiException();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $messages = $messageService->getAllByDestUserPagination($user, $page, $limit);

        return $this->json(ApiResponse::get($messages, 200, [
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
            ]
        ]),
            200,
            [],
            ['groups' => ['message', 'user']]
        );
    }

    /**
     * Get Message By ID (Only Seller)
     * @OA\Response(
     *     response=200,
     *     description="Return one of your messages by ID if you're seller",
     *     @Model(type=Message::class, groups={"message", "user"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="User is not a seller"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Message not found"
     * )
     * @param int $messageId
     * @param UserService $userService
     * @param MessageService $messageService
     * @return Response
     */
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
