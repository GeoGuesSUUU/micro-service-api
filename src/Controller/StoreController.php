<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ProductNotFoundApiException;
use App\Exception\StoreNotFoundApiException;
use App\Service\ProductService;
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
        int       $id,
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

    #[Route('/{storeId}/products/{productId}', name: 'app_store_items_available', methods: ['GET'], format: 'application/json')]
    public function available(
        int       $storeId,
        int       $productId,
        StoreService $storeService
    ): Response
    {
        $product = $storeService->getProduct($storeId, $productId);
        if (is_null($product)) throw new ProductNotFoundApiException();
        return $this->json(ApiResponse::get($product),
            200,
            [],
            ['groups' => ['product']]
        );
    }

    #[Route('/{storeId}/products/{productId}', name: 'app_store_add_product', methods: ['POST'], format: 'application/json')]
    public function addProduct(
        int       $storeId,
        int       $productId,
        StoreService $storeService,
        ProductService $productService,
        Request $request
    ): Response
    {
        $store = $storeService->get($storeId);
        if (is_null($store)) throw new StoreNotFoundApiException();

        $product = $productService->get($productId);
        if (is_null($product)) throw new ProductNotFoundApiException();

        $quantity = $request->get('quantity');
        $price = $request->get('price');

        $storeService->addProductInStore($store, $product, $quantity, $price);

        return $this->json(ApiResponse::get($store),
            200,
            [],
            ['groups' => ['store', 'store:products', 'product']]
        );
    }

    #[Route('/{storeId}/products/{productId}', name: 'app_store_add_product', methods: ['DELETE'], format: 'application/json')]
    public function removeProduct(
        int       $storeId,
        int       $productId,
        StoreService $storeService,
        ProductService $productService,
    ): Response
    {
        $store = $storeService->get($storeId);
        if (is_null($store)) throw new StoreNotFoundApiException();

        $product = $storeService->getProduct($storeId, $productId);
        if (is_null($product)) throw new ProductNotFoundApiException();

        $storeService->removeProductInStore($store, $product);

        return $this->json(ApiResponse::get($store),
            200,
            [],
            ['groups' => ['store', 'store:products', 'product']]
        );
    }
}
