<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 300; $i++) {
            $product = new Product();
            $product
                ->setName($faker->word)
                ->setDescription($faker->sentence)
                ->setPrice($faker->randomFloat(2,1,500));
            $manager->persist($product);
        }
        $manager->flush();
    }
}
