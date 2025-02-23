<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product;
        $product->setName("Potato");
        $product->setDescription("A starchy tuber that grows underground");
        $product->setSize(1);

        $manager->persist($product);

        $product = new Product;
        $product->setName("Watermelon");
        $product->setDescription("A large, sweet, and juicy fruit that belongs to the cucumber family");
        $product->setSize(5);
        
        $manager->persist($product);

        $manager->flush();
    }
}
