<?php

namespace App\Services;

use App\Entity\Commande;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class CalculeMontantCommandeService{

    private ?TokenInterface $token;

    public function __construct( TokenStorageInterface $tokenStorage) {

        $this->token = $tokenStorage->getToken();

    }

    public function calcule(Commande $commande){
        $montant = 0;

        if(count($commande->getTailleBoissonCommandes()) > 0 ){
            foreach ($commande->getTailleBoissonCommandes() as $tailleBC) {

                $prix = $tailleBC->getTailleBoisson()->getTaille()->getPrix();
                $montant = $montant + ($tailleBC->getQuantite() * $prix);

            }
        } 
        
        if($commande->getQuartier() != null){

            $montant = $montant + $commande->getQuartier()->getZone()->getPrix();

        }

        if(count($commande->getBurgerCommandes()) > 0){

            foreach ($commande->getBurgerCommandes() as $burgerCom) {

                $prix = $burgerCom->getBurger()->getPrix();
                $montant = $montant + ($burgerCom->getQuantite() * $prix);

            }

        }

        if(count($commande->getMenuCommandeTailleBoissons()) > 0){
            
            foreach ($commande->getMenuCommandeTailleBoissons() as $menuCTB) {

                $prix = $menuCTB->getMenu()->getPrix();
                $montant = $montant + ($menuCTB->getQuantite() * $prix);
                
            }

        }

        if(count($commande->getPortionFriteCommandes())){

            foreach ($commande->getPortionFriteCommandes() as $portionFrite) {
                $prix = $portionFrite->getPortionFrite()->getPrix();
                $montant = $montant + ($portionFrite->getQuantite() * $prix);

            }

        }

        $commande->setMontant($montant);
        

        return $commande;
    }

}