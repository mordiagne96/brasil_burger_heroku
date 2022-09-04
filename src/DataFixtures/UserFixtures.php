<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {

        for ($i=0; $i <4 ; $i++) { 
            # code...
            $ac = new User();
            $ac->setLogin("gestionnaire".$i."@gmail.com")
                ->setPassword($this->hasher->hashPassword($ac,"passer"))
                ->setRoles(["ROLE_GESTIONNAIRE"]);
            $manager->persist($ac);
        }
        
        $manager->flush();
    }
}
