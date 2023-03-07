<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Slot;
use App\Entity\Store;
use App\Entity\StoreProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GlobalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Product();
        $product1->setName('Sandwich triangle');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Sandwich poulet tex-mex');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Haribo');
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setName('Twitter');
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setName('Massey Ferguson 6475 DYNA 6');
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setName('Clavier');
        $manager->persist($product6);

        $product7 = new Product();
        $product7->setName('Emmanuel Macron');
        $manager->persist($product7);

        $product8 = new Product();
        $product8->setName('Nokia 3310');
        $manager->persist($product8);

        $product9 = new Product();
        $product9->setName('Renault Modus');
        $manager->persist($product9);

        $product10 = new Product();
        $product10->setName('Banane');
        $manager->persist($product10);

        $product11 = new Product();
        $product11->setName('Cailloux');
        $manager->persist($product11);


        $store1 = new Store();
        $store1->setName('Alexis le gros bidon');
        $store1->setZip('01000');
        $store1->setAddress('584 Mega Avenue du futur');
        $manager->persist($store1);

        $store2 = new Store();
        $store2->setName('Magasin pas ouf du tout');
        $store2->setZip('69699');
        $store2->setAddress('753 rue des clochards');
        $manager->persist($store2);


        $storeProduct1 = new StoreProduct();
        $storeProduct1->setProduct($product1);
        $storeProduct1->setStore($store1);
        $storeProduct1->setQuantity(rand(1, 1000));
        $storeProduct1->setPrice(rand(1, 99999));
        $manager->persist($storeProduct1);

        $storeProduct2 = new StoreProduct();
        $storeProduct2->setProduct($product2);
        $storeProduct2->setStore($store1);
        $storeProduct2->setQuantity(rand(1, 1000));
        $storeProduct2->setPrice(rand(1, 99999));
        $manager->persist($storeProduct2);

        $storeProduct3 = new StoreProduct();
        $storeProduct3->setProduct($product3);
        $storeProduct3->setStore($store1);
        $storeProduct3->setQuantity(rand(1, 1000));
        $storeProduct3->setPrice(rand(1, 99999));
        $manager->persist($storeProduct3);

        $storeProduct4 = new StoreProduct();
        $storeProduct4->setProduct($product4);
        $storeProduct4->setStore($store1);
        $storeProduct4->setQuantity(rand(1, 1000));
        $storeProduct4->setPrice(rand(1, 99999));
        $manager->persist($storeProduct4);

        $storeProduct5 = new StoreProduct();
        $storeProduct5->setProduct($product5);
        $storeProduct5->setStore($store1);
        $storeProduct5->setQuantity(rand(1, 1000));
        $storeProduct5->setPrice(rand(1, 99999));
        $manager->persist($storeProduct5);

        $storeProduct6 = new StoreProduct();
        $storeProduct6->setProduct($product6);
        $storeProduct6->setStore($store1);
        $storeProduct6->setQuantity(rand(1, 1000));
        $storeProduct6->setPrice(rand(1, 99999));
        $manager->persist($storeProduct6);

        $storeProduct13 = new StoreProduct();
        $storeProduct13->setProduct($product7);
        $storeProduct13->setStore($store1);
        $storeProduct13->setQuantity(rand(1, 1000));
        $storeProduct13->setPrice(rand(1, 99999));
        $manager->persist($storeProduct13);

        $storeProduct14 = new StoreProduct();
        $storeProduct14->setProduct($product8);
        $storeProduct14->setStore($store1);
        $storeProduct14->setQuantity(rand(1, 1000));
        $storeProduct14->setPrice(rand(1, 99999));
        $manager->persist($storeProduct14);


        $storeProduct15 = new StoreProduct();
        $storeProduct15->setProduct($product4);
        $storeProduct15->setStore($store2);
        $storeProduct15->setQuantity(rand(1, 1000));
        $storeProduct15->setPrice(rand(1, 99999));
        $manager->persist($storeProduct15);

        $storeProduct16 = new StoreProduct();
        $storeProduct16->setProduct($product5);
        $storeProduct16->setStore($store2);
        $storeProduct16->setQuantity(rand(1, 1000));
        $storeProduct16->setPrice(rand(1, 99999));
        $manager->persist($storeProduct16);

        $storeProduct7 = new StoreProduct();
        $storeProduct7->setProduct($product6);
        $storeProduct7->setStore($store2);
        $storeProduct7->setQuantity(rand(1, 1000));
        $storeProduct7->setPrice(rand(1, 99999));
        $manager->persist($storeProduct7);

        $storeProduct8 = new StoreProduct();
        $storeProduct8->setProduct($product7);
        $storeProduct8->setStore($store2);
        $storeProduct8->setQuantity(rand(1, 1000));
        $storeProduct8->setPrice(rand(1, 99999));
        $manager->persist($storeProduct8);

        $storeProduct9 = new StoreProduct();
        $storeProduct9->setProduct($product8);
        $storeProduct9->setStore($store2);
        $storeProduct9->setQuantity(rand(1, 1000));
        $storeProduct9->setPrice(rand(1, 99999));
        $manager->persist($storeProduct9);

        $storeProduct10 = new StoreProduct();
        $storeProduct10->setProduct($product9);
        $storeProduct10->setStore($store2);
        $storeProduct10->setQuantity(rand(1, 1000));
        $storeProduct10->setPrice(rand(1, 99999));
        $manager->persist($storeProduct10);

        $storeProduct11 = new StoreProduct();
        $storeProduct11->setProduct($product10);
        $storeProduct11->setStore($store2);
        $storeProduct11->setQuantity(rand(1, 1000));
        $storeProduct11->setPrice(rand(1, 99999));
        $manager->persist($storeProduct11);

        $storeProduct12 = new StoreProduct();
        $storeProduct12->setProduct($product11);
        $storeProduct12->setStore($store2);
        $storeProduct12->setQuantity(rand(1, 1000));
        $storeProduct12->setPrice(rand(1, 99999));
        $manager->persist($storeProduct12);

        $slot1 = new Slot();
        $slot1->setStore($store1);
        $slot1->setStartDate(new \DateTime('2023-09-08'));
        $slot1->setEndDate(new \DateTime('2023-09-10'));
        $manager->persist($slot1);

        $slot2 = new Slot();
        $slot2->setStore($store1);
        $slot2->setStartDate(new \DateTime('2023-07-01'));
        $slot2->setEndDate(new \DateTime('2023-07-05'));
        $manager->persist($slot2);

        $slot3 = new Slot();
        $slot3->setStore($store1);
        $slot3->setStartDate(new \DateTime('2023-04-25'));
        $slot3->setEndDate(new \DateTime('2023-04-26'));
        $manager->persist($slot3);


        $slot4 = new Slot();
        $slot4->setStore($store2);
        $slot4->setStartDate(new \DateTime('2023-09-08'));
        $slot4->setEndDate(new \DateTime('2023-09-10'));
        $manager->persist($slot4);

        $slot5 = new Slot();
        $slot5->setStore($store2);
        $slot5->setStartDate(new \DateTime('2023-07-01'));
        $slot5->setEndDate(new \DateTime('2023-07-05'));
        $manager->persist($slot5);

        $slot6 = new Slot();
        $slot6->setStore($store2);
        $slot6->setStartDate(new \DateTime('2023-04-25'));
        $slot6->setEndDate(new \DateTime('2023-04-26'));
        $manager->persist($slot6);

        $manager->flush();
    }
}