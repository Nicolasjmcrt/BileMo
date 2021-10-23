<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Customer;
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
                ->setReference($faker->deviceSerialNumber)
                ->setVat(20.00)
                ->setPrice($faker->price(650.00, 1299.00))
                ->setCreationDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 weeks', '-1 days')))
                ->setQuantity(mt_rand(8, 27));

            $manager->persist($product);

        }

        for ($c=0; $c < 2; $c++) { 
            $customer = new Customer;
            $customer->setName($faker->company())
                ->setUserName($faker->userName())
                ->setPassword($faker->password());

            $manager->persist($customer);

            for ($u=0; $u < mt_rand(3, 7); $u++) { 
                $user = new User;
                $user->setUsername($faker->userName())
                    ->setPassword($faker->password())
                    ->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->email())
                    ->setCreationDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 weeks', '-1 days')))
                    ->setCustomer($customer);

                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
