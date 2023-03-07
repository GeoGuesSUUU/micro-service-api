<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\CommandProduct;
use App\Entity\Store;
use App\Entity\StoreProduct;
use App\Entity\User;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;

class CommandService
{

    public function __construct(
        private readonly CommandRepository $commandRepository,
        private readonly CommandProductRepository $commandProductRepository
    )
    {
    }

    public function create(Store $store, User $user): Command {
        $command = new Command();
        $command->setStore($store);
        $command->setUser($user);
        $this->commandRepository->save($command);
        return $command;
    }

    public function get(int $id): Command {
        return $this->commandRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param Command $command
     * @param StoreProduct[] $storeProducts
     * @return Command
     */
    public function addProductFromStoreProducts(Command $command, array $storeProducts): Command {
        foreach ($storeProducts as $item) {
            if ($item->getQuantity() <= 0) continue;

            $exist = $command->getCommandProducts()->filter(
                fn($i) => $i->getProduct()->getId() === $item->getProduct()->getId()
            );
            $exist = $exist->getValues()[0] ?? null;
            if (
                is_null($exist)
            ) {
                $exist = new CommandProduct();
                $exist->setProduct($item->getProduct());
                $exist->setPrice($item->getPrice());
                $exist->setQuantity(0);
            }
            $exist->setQuantity($exist->getQuantity() + 1);
            $item->setQuantity($item->getQuantity() - 1);
            $command->addCommandProduct($exist);
        }
        return $command;
    }

    public function saveCommand(Command $command, bool $flush = false): Command {
        $this->commandRepository->save($command, $flush);
        return $command;
    }
}