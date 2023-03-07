<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Store;
use App\Entity\StoreProduct;
use App\Repository\ProductRepository;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;

class StoreService
{

    public function __construct(
        private readonly StoreRepository $storeRepository,
        private readonly StoreProductRepository $storeProductRepository,
        private readonly ProductRepository $productRepository,
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
}
