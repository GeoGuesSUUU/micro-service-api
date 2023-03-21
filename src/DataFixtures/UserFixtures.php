<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setName('Admin');
        $adminUser->setEmail('admin@admin.ad');
        $adminUser->setRoles(['ROLE_ADMIN']);

        $adminPassword = $this->hasher->hashPassword($adminUser, 'Admin123');
        $adminUser->setPassword($adminPassword);

        $manager->persist($adminUser);

        $sellerUser = new User();
        $sellerUser->setName('Seller');
        $sellerUser->setEmail('seller@seller.se');
        $sellerUser->setRoles(['ROLE_SELLER']);

        $sellerPassword = $this->hasher->hashPassword($sellerUser, 'Seller123');
        $sellerUser->setPassword($sellerPassword);

        $manager->persist($sellerUser);

        $clientUser = new User();
        $clientUser->setName('Client');
        $clientUser->setEmail('client@client.cl');
        $clientUser->setRoles(['ROLE_USER']);

        $clientPassword = $this->hasher->hashPassword($clientUser, 'Client123');
        $clientUser->setPassword($clientPassword);

        $manager->persist($clientUser);
        $manager->flush();
    }
}