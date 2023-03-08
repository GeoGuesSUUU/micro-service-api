<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class StoreCommandInput
{
    #[Groups('store:command:input')]
    /** @var int[]|null  */
    private ?array $products;

    #[Groups('store:command:input')]
    private int $command;

    /**
     * @return array|null
     */
    public function getProducts(): ?array
    {
        return $this->products;
    }

    /**
     * @param array|null $products
     */
    public function setProducts(?array $products): void
    {
        $this->products = $products;
    }

    /**
     * @return int
     */
    public function getCommand(): int
    {
        return $this->command;
    }

    /**
     * @param int $command
     */
    public function setCommand(int $command): void
    {
        $this->command = $command;
    }
}
