<?php
namespace App\Services;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Gestionnaire;
use App\Entity\Livreur;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordhasherRoleService{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hasherPassword(User $user){

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
            );
        
            $user->setPassword($hashedPassword);
            
            if($user instanceof Gestionnaire){
                $user->setRoles(['ROLE_GESTIONNAIRE']);
            }
            if($user instanceof Client){
                $user->setRoles(['ROLE_CLIENT']);
            }
            if($user instanceof Livreur){
                $user->setRoles(['ROLE_LIVREUR']);
            }

            return $user;

    }

}