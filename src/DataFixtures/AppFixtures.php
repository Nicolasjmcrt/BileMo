<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Product;
use Liior\Faker\Prices;
use Bezhanov\Faker\Provider\Device;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Device($faker));

        for ($p=0; $p < 20; $p++) { 
            $product = new Product;
            $product->setName($faker->deviceModelName)
                ->setDescription($faker->sentence())
                ->setPrice($faker->price(650, 1299))
                ->setCreationDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 weeks', '-1 days')))
                ->setQuantity(mt_rand(8, 27));

            $manager->persist($product);

        }

        $manager->flush();
    }
}
