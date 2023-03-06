<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Store;
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
}
