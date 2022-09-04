<?php

namespace App\Services;

use App\Entity\Livraison;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class CalculeMontantLivraisonService{

    private ?TokenInterface $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }

    public function calculeMontantTotal(Livraison $livraison){
        
        $montant = 0;

        foreach ($livraison->getCommandes() as $commande) {
            $montant = $montant + $commande->getMontant();
            $commande->setGestionnaire($this->token->getUser());
        }

        $livraison->setMontantTotal($montant);

        return $livraison;
    }


}