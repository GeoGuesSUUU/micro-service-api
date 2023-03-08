<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;

class MessageService
{

    public function __construct(
        private readonly MessageRepository $messageRepository,
    )
    {
    }

    /**
     * @param User $user
     * @return Message[]
     */
    public function getAllByDestUser(User $user): array {
        return $this->messageRepository->findBy(['seller' => $user->getId()]);
    }

    /**
     * @param User $user
     * @param int $page
     * @param int $limit
     * @return Message[]
     */
    public function getAllByDestUserPagination(User $user, int $page, int $limit): array {
        return $this->messageRepository->findAllBySellerWithPagination($user->getId(), $page, $limit);
    }

    /**
     * @param User $user
     * @param int $id
     * @return Message|null
     */
    public function getOneByDestUser(User $user, int $id): Message | null {
        return $this->messageRepository->findOneBy(['id' => $id, 'seller' => $user->getId()]);
    }

    public function send(?User $srcUser, User $destUser, string $content, bool $flush = false): Message {
        $message = new Message();
        $message->setSendDate(new \DateTime());
        $message->setUser($srcUser);
        $message->setSeller($destUser);
        $message->setContent($content);
        $this->messageRepository->save($message, $flush);
        return $message;
    }
}
