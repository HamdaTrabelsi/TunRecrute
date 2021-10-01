<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->employerload($manager);
        $this->candidatload($manager);
        // $this->annonceload($manager);
        $manager->flush();
    }



    public function employerload(ObjectManager $manager){
        for($i=1;$i<7;$i++){
            $user = new User();
            $user->setEmail("emp$i@gmail.com");
            $user->setRoles(['ROLE_EMPLOYER']);
            $user->setPassword('$argon2i$v=19$m=65536,t=4,p=1$Z0lWY3JiYU5xR2FrWlZDag$nLRohXxjR+SnlgABH0qff/IsXqIvQeovacPrYVWoG3I');
            $user->setAddress("Address $i");
            $user->setPicture("good.png");
            for($j=1;$j<10;$j++) {
                $annonce = new Annonce();
                $annonce->setTitle("title $j");
                $annonce->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
                $annonce->setPosted(new \DateTime());
                $annonce->setUser($user);
                $annonce->setSalaire("400-600");
                $annonce->setContrat("CDI");
                $annonce->setExperience("5");
                $annonce->setHeuresTravail("25");
                $annonce->setAdresseTravail("Ariana, Tehnopole");
                $manager->persist($annonce);
            }

            $manager->persist($user);
        }
        $manager->flush();
    }
    public function candidatload(ObjectManager $manager){
        for($i=1;$i<6;$i++){
            $user = new User();
            $user->setEmail("cand$i@gmail.com");
            $user->setRoles(['ROLE_CANDIDAT']);
            $user->setPassword('$argon2i$v=19$m=65536,t=4,p=1$Z0lWY3JiYU5xR2FrWlZDag$nLRohXxjR+SnlgABH0qff/IsXqIvQeovacPrYVWoG3I');
            $user->setAddress("Address $i");

            $manager->persist($user);
        }
        $manager->flush();
    }

   /*public function annonceload(ObjectManager $manager){
        for($i=1;$i<=20;$i++){
            $user=new User();
            $annonce = new Annonce();
            $annonce->setTitle("title $i");
            $annonce->setDescription("description $i");
            $annonce->setPosted(new \DateTime());
            $annonce->setUser($user->getId(rand(1,5)));
            $manager->persist($annonce);
        }
        $manager->flush();
    }*/
}
