<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\CommandProduct;
use App\Entity\Product;
use App\Entity\Store;
use App\Entity\StoreProduct;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;

class StoreService
{

    public function __construct(
        private readonly StoreRepository $storeRepository,
        private readonly StoreProductRepository $storeProductRepository
    )
    {
    }

    /**
     * @param int $id
     * @return Store|null
     */
    public function get(int $id): Store|null
    {
        return $this->storeRepository->findOneBy(['id' => $id]);
    }

    /**
     * @return Store[]
     */
    public function getAll(): array
    {
        return $this->storeRepository->findAll();
    }

    /**
     * @param string $zip
     * @return Store[]
     */
    public function getAllByZip(string $zip): array
    {
        return $this->storeRepository->findBy(['zip' => $zip]);
    }

    /**
     * @param int $storeId
     * @param int $productId
     * @return Product|null
     */
    public function getProduct(int $storeId, int $productId): Product|null
    {
        $res = $this->storeProductRepository->findOneBy(['store' => $storeId, 'product' => $productId]);
        if (is_null($res)) return null;
        return $res->getProduct();
    }

    public function addProductInStore(Store $store, Product $product, int $quantity = 0, int $price = 0): Store | null
    {
        $storeProduct = $this->storeProductRepository->findOneBy(['store' => $store, 'product' => $product]);
        if (is_null($storeProduct)) {
            $storeProduct = new StoreProduct();
            $storeProduct->setStore($store);
            $storeProduct->setProduct($product);
            $storeProduct->setQuantity($quantity);
            $storeProduct->setPrice($price);
        } else {
            $storeProduct->setQuantity($storeProduct->getQuantity() + $quantity);
            $storeProduct->setPrice($storeProduct->getPrice() + $price);
        }

        $this->storeProductRepository->save($storeProduct, true);

        return $store;
    }

    public function removeProductInStore(Store $store, Product $product): Store | null
    {
        $storeProduct = $this->storeProductRepository->findOneBy(['store' => $store, 'product' => $product]);
        $this->storeProductRepository->remove($storeProduct, true);

        return $store;
    }

    /**
     * @param int $storeId
     * @param int[] $productIds
     * @return StoreProduct|null
     */
    public function getStoreProduct(int $storeId, array $productIds): array|null
    {
        $res = $this->storeProductRepository->findBy(['store' => $storeId, 'product' => $productIds]);
        if (is_null($res)) return null;
        return $res;
    }

    /**
     * @param Store $store
     * @param Command $command
     * @param int[] $ids
     * @return Command
     */
    public function buyItems(Store $store, Command $command, array $ids): Command {
        $items = $this->storeProductRepository->findBy(['product' => $ids, 'store' => $store->getId()]);
        foreach ($items as $item) {
            $exist = $command->getCommandProducts()->findFirst(
                fn($i) => $i->getProduct()->getId() === $item->getProduct()->getId()
            );
            if (
                is_null($exist)
            ) {
                $exist = new CommandProduct();
                $exist->setProduct($item->getProduct());
                $exist->setPrice($item->getPrice());
                $exist->setQuantity(0);
            }
            $exist->setQuantity($exist->getQuantity() + 1);
            $command->addCommandProduct($exist);
        }
        return $command;
    }
}
