<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\CommandProduct;
use App\Entity\Store;
use App\Entity\StoreCommandProductInput;
use App\Entity\StoreProduct;
use App\Entity\User;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CommandService
{

    public function __construct(
        private readonly CommandRepository $commandRepository,
        private readonly CommandProductRepository $commandProductRepository,
        private readonly MailerInterface $mailer
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

    public function get(int $id): Command | null {
        return $this->commandRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param array $products
     * @param StoreProduct $storeProduct
     * @return StoreCommandProductInput|null
     */
    private function getProductParamByStoreProduct(array $products, StoreProduct $storeProduct): StoreCommandProductInput|null {
        foreach ( $products as $product ) {
            if (
                isset($product['productId']) &&
                isset($product['quantity']) &&
                $product['productId'] === $storeProduct->getId()
            ) {
                $scp = new StoreCommandProductInput();
                $scp->setProductId($product['productId']);
                $scp->setQuantity($product['quantity']);
                return $scp;
            }
        }
        return null;
    }

    /**
     * @param Command $command
     * @param StoreProduct[] $storeProducts
     * @param array $productIds
     * @return Command
     */
    public function addProductFromStoreProducts(Command $command, array $storeProducts, array $productIds): Command {
        foreach ($storeProducts as $item) {
            $prod = $this->getProductParamByStoreProduct($productIds, $item);
            if (
                is_null($prod ?? null) ||
                $item->getQuantity() <= 0 ||
                $prod->getQuantity() > $item->getQuantity()
            ) continue;

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
            $exist->setQuantity($exist->getQuantity() + $prod->getQuantity());
            $item->setQuantity($item->getQuantity() - $prod->getQuantity());
            $command->addCommandProduct($exist);
        }
        return $command;
    }

    public function saveCommand(Command $command, bool $flush = false): Command {
        $this->commandRepository->save($command, $flush);
        return $command;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function validCommand(Command $command, User $user, bool $flush = false): Command | null {
        if (is_null($command->getSlot())) return null;
        $command->setIsValid(true);
        $this->commandRepository->save($command, $flush);

        $email = (new Email())
            ->from('grosbidon6969@gmail.com')
            ->to($user->getEmail())
            ->subject('Command validated !')
            ->text('Hello ' . $user->getName() . ' your command ' . $command->getId() . ' is validated !');

        $this->mailer->send($email);

        return $command;
    }
}
