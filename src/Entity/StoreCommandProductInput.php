<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class StoreCommandProductInput
{
    #[Groups('store:command:input')]
    private int $productId;

    #[Groups('store:command:input')]
    private int $quantity;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
