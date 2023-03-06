<?php

namespace App\Service;

use App\Entity\Store;
use App\Repository\StoreRepository;

class StoreService
{

    public function __construct(
        private readonly StoreRepository $storeRepository
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
}