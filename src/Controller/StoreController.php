<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\StoreNotFoundApiException;
use App\Service\StoreService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/stores')]
class StoreController extends AbstractController
{
    #[Route('/nearest/{zip}', name: 'app_store_nearest', methods: ['GET'], format: 'application/json')]
    public function nearest(
        string       $zip,
        StoreService $storeService
    ): Response
    {
        $stores = $storeService->getAllByZip($zip);
        return $this->json(ApiResponse::get($stores),
            200,
            [],
            ['groups' => ['store']]
        );
    }

    #[Route('/{id}/products', name: 'app_store_products', methods: ['GET'], format: 'application/json')]
    public function products(
        string       $id,
        StoreService $storeService
    ): Response
    {
        $store = $storeService->get($id);
        if (is_null($store)) throw new StoreNotFoundApiException();
        return $this->json(ApiResponse::get($store),
            200,
            [],
            ['groups' => ['store', 'store:products', 'product']]
        );
    }
}
