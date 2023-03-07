<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Store;
use App\Entity\Product;
use App\Entity\Command;
use App\Entity\StoreCommandInput;
use App\Entity\SlotAvailableDTO;
use App\Exception\BadRequestApiException;
use App\Exception\ProductNotFoundApiException;
use App\Exception\SlotAlreadyBookedApiException;
use App\Exception\SlotNotFoundApiException;
use App\Exception\StoreNotFoundApiException;
use App\Service\CommandService;
use App\Service\ProductService;
use App\Service\SlotService;
use App\Service\StoreService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use OpenApi\Attributes as OAA;

#[OAA\Tag(name: 'Store (Products, Commands & Slots)')]
#[Security(name: 'Bearer')]
#[Route('/api/stores')]
class StoreController extends AbstractController
{
    /**
     * Get all Store By ZIP code (Only Seller)
     * @OA\Parameter(name="page", in="query")
     * @OA\Parameter(name="limit", in="query")
     * @OA\Response(
     *     response=200,
     *     description="Return all store by zip code",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Store::class, groups={"store"}))
     *     )
     * )
     * @param string $zip
     * @param StoreService $storeService
     * @param Request $request
     * @return Response
     */
    #[Route('/nearest/{zip}', name: 'app_store_nearest', methods: ['GET'], format: 'application/json')]
    public function nearest(
        string       $zip,
        StoreService $storeService,
        Request $request
    ): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $stores = $storeService->getAllByZipPagination($zip, $page, $limit);
        return $this->json(ApiResponse::get($stores),
            200,
            [],
            ['groups' => ['store']]
        );
    }

    /**
     * Get Store By ID and all products
     * @OA\Response(
     *     response=200,
     *     description="Return one store by ID and it products",
     *     @Model(type=Store::class, groups={"store", "store:products", "product"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Store not found"
     * )
     * @param int $id
     * @param StoreService $storeService
     * @return Response
     */
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

    /**
     * Get Product By ID from Store
     * @OA\Response(
     *     response=200,
     *     description="Return one product by ID",
     *     @Model(type=Product::class, groups={"product"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Product not found"
     * )
     * @param int $storeId
     * @param int $productId
     * @param StoreService $storeService
     * @return Response
     */
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

    /**
     * Add Product By ID in selected Store
     * @OA\Response(
     *     response=200,
     *     description="Return one store by ID and it products",
     *     @Model(type=Store::class, groups={"store", "store:products", "product"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Product not found"
     * )
     * @param int $storeId
     * @param int $productId
     * @param StoreService $storeService
     * @param ProductService $productService
     * @param Request $request
     * @return Response
     */
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

        $content = json_decode($request->getContent(), true);
        if (is_null($content['quantity'] ?? null) || is_null($content['price'] ?? null)) throw new BadRequestApiException();

        $quantity = $content['quantity'];
        $price = $content['price'];

        $storeService->addProductInStore($store, $product, $quantity, $price);

        return $this->json(ApiResponse::get($store),
            200,
            [],
            ['groups' => ['store', 'store:products', 'product']]
        );
    }

    /**
     * Remove Product By ID in selected Store
     * @OA\Response(
     *     response=200,
     *     description="Return one store by ID and it products",
     *     @Model(type=Store::class, groups={"store", "store:products", "product"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Product not found"
     * )
     * @param int $storeId
     * @param int $productId
     * @param StoreService $storeService
     * @param ProductService $productService
     * @return Response
     */
    #[Route('/{storeId}/products/{productId}', name: 'app_store_remove_product', methods: ['DELETE'], format: 'application/json')]
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

    /**
     * Command Products from select Store Products
     * @OA\RequestBody(@Model(type=StoreCommandInput::class, groups={"store:command:input"}))
     * @OA\Response(
     *     response=200,
     *     description="Return Command",
     *     @Model(type=Command::class, groups={"command", "command:products", "product", "user", "store", "slot"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Store not found"
     * )
     * @param int $storeId
     * @param StoreService $storeService
     * @param CommandService $commandService
     * @param Request $request
     * @return Response
     */
    #[Route('/{storeId}/commands', name: 'app_store_commands', methods: ['POST'], format: 'application/json')]
    public function commandProduct(
        int       $storeId,
        StoreService $storeService,
        CommandService $commandService,
        Request $request
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        $productIds = $content['products'];
        if (is_null($productIds)) throw new ProductNotFoundApiException();

        $commandId = $content['command'];

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
            ['groups' => ['command', 'command:products', 'product', 'user', 'store', 'slot']]
        );
    }

    /**
     * Get all Slots form Store (Only Client)
     * @OA\Parameter(name="page", in="query")
     * @OA\Parameter(name="limit", in="query")
     * @OA\Response(
     *     response=200,
     *     description="Return all slots from store",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SlotAvailableDTO::class, groups={"slot:available"}))
     *     )
     * )
     * @param int $storeId
     * @param StoreService $storeService
     * @param SlotService $slotService
     * @param Request $request
     * @return Response
     */
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

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $slots = $slotService->availableListPagination($store, $page, $limit);
        return $this->json(ApiResponse::get($slots),
            200,
            [],
            ['groups' => ['slot:available']]
        );
    }

    /**
     * Book a Slot form Store (Only Client)
     * @OA\Response(
     *     response=200,
     *     description="Return all slots from store",
     *     @Model(type=SlotAvailableDTO::class, groups={"slot:available"})
     * )
     * @OA\Response(
     *     response=404,
     *     description="Slot not found"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Slot already booked"
     * )
     * @param int $storeId
     * @param int $slotId
     * @param StoreService $storeService
     * @param SlotService $slotService
     * @param Request $request
     * @return Response
     */
    #[Route('/{storeId}/slots/{slotId}/booking', name: 'app_store_slots_booking', methods: ['POST'], format: 'application/json')]
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
}
