<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\CommandNotFoundApiException;
use App\Exception\ProductNotFoundApiException;
use App\Exception\SlotAlreadyBookedApiException;
use App\Exception\SlotNotFoundApiException;
use App\Exception\StoreNotFoundApiException;
use App\Service\CommandService;
use App\Service\SlotService;
use App\Service\StoreService;
use App\Service\UserService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\CommandNotFoundException;
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

    #[Route('/{storeId}/commands', name: 'app_store_commands', methods: ['POST'], format: 'application/json')]
    public function buy(
        int       $storeId,
        StoreService $storeService,
        CommandService $commandService,
        Request $request
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $productIds = $request->query->get('products');
        if (is_null($productIds)) throw new ProductNotFoundApiException();
        $productIds = explode(",", $productIds);
        $productIds = array_map(fn($i) => intval($i), $productIds);

        $commandId = $request->query->get('command');

        $store = $storeService->get($storeId);
        if (is_null($store)) throw new StoreNotFoundApiException();

        if (is_null($commandId)) {
            $command = $commandService->create($store, $user);
        } else {
            $command = $commandService->get($commandId);
        }


        $storeProducts = $storeService->getStoreProduct($store->getId(), $productIds);
        $command = $commandService->addProductFromStoreProducts($command, $storeProducts);
        $command = $commandService->saveCommand($command, true);
        return $this->json(ApiResponse::get($command),
            200,
            [],
            ['groups' => ['command', 'command:products', 'product']]
        );
    }

    #[Route('/{storeId}/slots', name: 'app_store_slots', methods: ['GET'], format: 'application/json')]
    public function slots(
        int       $storeId,
        StoreService $storeService,
        SlotService $slotService,
        Request $request
    ): Response
    {

        $store = $storeService->get($storeId);
        if (is_null($store)) throw new StoreNotFoundApiException();

        $slots = $slotService->availableList($store);
        return $this->json(ApiResponse::get($slots),
            200,
            [],
            ['groups' => ['slot:available']]
        );
    }

    #[Route('/{storeId}/slots/{slotId}/booking', name: 'app_store_slots', methods: ['POST'], format: 'application/json')]
    public function slotBooking(
        int       $storeId,
        int       $slotId,
        StoreService $storeService,
        SlotService $slotService,
        Request $request
    ): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $store = $storeService->get($storeId);
        if (is_null($store ?? null)) throw new StoreNotFoundApiException();

        $slot = $slotService->getByIdAndStore($slotId, $store);
        if (is_null($slot ?? null)) throw new SlotNotFoundApiException();

        $slots = $slotService->bookSlot($slot, $user, true);
        if (is_null($slots ?? null)) throw new SlotAlreadyBookedApiException();
        return $this->json(ApiResponse::get($slots),
            200,
            [],
            ['groups' => ['slot:available']]
        );
    }

//    #[Route('/{storeId}/products/buy', name: 'app_store_items_buy', methods: ['GET'], format: 'application/json')]
//    public function buy(
//        int       $storeId,
//        StoreService $storeService,
//        Request $request
//    ): Response
//    {
//        /** @var User $user */
//        $user = $this->getUser();
//
//        $products = $request->query->get('product');
//        // TODO : finish this optional function
//
//        $store = $storeService->get($storeId);
//        if (is_null($store)) throw new StoreNotFoundApiException();
//
//        $command = $storeService->buyItems($store);
//        return $this->json(ApiResponse::get($command),
//            200,
//            [],
//            ['groups' => ['product']]
//        );
//    }
}
