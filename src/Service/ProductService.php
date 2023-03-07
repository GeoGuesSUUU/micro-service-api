<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Store;
use App\Entity\StoreProduct;
use App\Repository\ProductRepository;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;

class ProductService
{

    public function __construct(
        private readonly ProductRepository $productRepository,
    )
    {
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function get(int $id): Product|null
    {
        return $this->productRepository->findOneBy(['id' => $id]);
    }

    /**
     * @return Product[]
     */
    public function getAll(): array
    {
        return $this->productRepository->findAll();
    }
}
