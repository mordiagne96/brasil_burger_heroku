<?php
namespace App\Services;
use App\Entity\Menu;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CalculePrixMenuService{

    private ?TokenInterface $token;

    public function __construct(TokenStorageInterface $tokenStorage) {

        $this->token = $tokenStorage->getToken();

    }

    public function calculer(Menu $menu){

        $montant = 0;
        foreach ($menu->getBurgerMenus() as $burgerMenu) {

            $montant = $montant + ($burgerMenu->getQuantite() * $burgerMenu->getBurger()->getPrix());

        }

        foreach ($menu->getTailleMenus() as $tailleMenu){
            
            $montant = $montant + ($tailleMenu->getQuantite() * $tailleMenu->getTaille()->getPrix());

        }

        // dd($menu->getTailles()[1]);

        $menu->setPrix($montant);

        return $menu;
    }

}