<?php

namespace App\Services;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ValidCommandeService{

    public function isValidCommande(Commande $commande){
        $tailleBoissonUpdate = [];
        foreach ($commande->getMenuCommandeTailleBoissons() as $menuCommandesTailleBoisson) {

            foreach ($menuCommandesTailleBoisson->getMenu()->getTailleMenus() as $tailleMenu) {
                $trouveTaille = false;
                foreach ($menuCommandesTailleBoisson->getTailleBoissons() as $tailleBoisson) {

                    if($tailleMenu->getTaille()->getId() == $tailleBoisson->getTaille()->getId()){
                        $trouveTaille = true;

                        if($tailleMenu->getQuantite() != $tailleBoisson->getQuantite()){
                            return new JsonResponse("La quantité taille-boisson est différent à la quantité fixé pour ce menu",Response::HTTP_BAD_REQUEST,[],true);
                        }

                        foreach ($tailleMenu->getTaille()->getTailleBoissons() as $tailleBoisStock) {                                                                                                                                                                                                                                                    
                            
                            if($tailleBoisStock->getMenuCommandeTailleBoisson() == null){

                                if($tailleBoisStock->getBoisson()->getId() == $tailleBoisson->getBoisson()->getId()){
    
                                    if($tailleBoisson->getQuantite() <= $tailleBoisStock->getQuantite()){
    
                                        $tailleBoissonUpdate[$tailleBoisStock->getId()] = $tailleBoisson->getQuantite();
    
                                    }else{
                                        
                                        return new JsonResponse("Stock Insuffisante pour ".$tailleBoisson->getBoisson()->getNom()." - ". $tailleBoisson->getTaille()->getLibelle() ,Response::HTTP_BAD_REQUEST,[],true);
                                    }
                                }

                            }  
                        }
                    }
                }
                if(!$trouveTaille){
                    return new JsonResponse("Vous avez entrer un taille qui n'existe pas sur le menu" ,Response::HTTP_BAD_REQUEST,[],true);
                }
            }
        }

        return $tailleBoissonUpdate;

    }

}