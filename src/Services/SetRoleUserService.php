<?php

namespace App\Services;

use App\Entity\Livreur;
use App\Entity\User;

final class SetRoleUserService{

    public function setRoleUser(User $user){
        
        if($user instanceof Livreur){
            $user->setRoles(["ROLE_LIVREUR"]);
        }

        return $user;
    }

}