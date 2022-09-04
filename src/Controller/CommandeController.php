<?php

namespace App\Controller;

use DateTime;
use App\Services\UpdateStockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Services\GenererNumeroCommandeService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CalculeMontantCommandeService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeController extends AbstractController
{
    private UpdateStockService $service;
    private ?TokenInterface $token;

    public function __construct(UpdateStockService $service, TokenStorageInterface $tokenStorage)
    {
        $this->service = $service;
        $this->token = $tokenStorage->getToken();
    }

    function __invoke(Request $request,EntityManagerInterface $entityManager, GenererNumeroCommandeService $numeroService, CalculeMontantCommandeService $montantservice)
    {
        $tailleBoissonUpdate=[];

        $commande = $request->get("data");

        if(count($commande->getMenuCommandeTailleBoissons()) == 0 && count($commande->getBurgerCommandes()) == 0){
            return new JsonResponse("Commande Invalide! Veuillez choisir un burger ou un menu SVP... " ,Response::HTTP_BAD_REQUEST,[],true);
        }



        foreach ($commande->getMenuCommandeTailleBoissons() as $menuCommandesTailleBoisson) {

            foreach ($menuCommandesTailleBoisson->getMenu()->getTailleMenus() as $tailleMenu) {
                $trouveTaille = false;
                foreach ($menuCommandesTailleBoisson->getTailleBoissons() as $tailleBoisson) {

                    if($tailleMenu->getTaille()->getId() == $tailleBoisson->getTaille()->getId()){
                        $trouveTaille = true;

                        if($tailleMenu->getQuantite() != $tailleBoisson->getQuantite()){
                            return new JsonResponse("La quantité taille-boisson depasse la quantité fixé pour ce menu",Response::HTTP_BAD_REQUEST,[],true);
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

        $numero = $numeroService->genererNumero();
        $commande->setNumeroCommande($numero);
        $commande = $montantservice->calcule($commande);
        $commande->setDate(new DateTime());
        $commande->setClient($this->token->getUser());
        $entityManager->persist($commande);
        $this->service->updateStock($tailleBoissonUpdate);
        $entityManager->flush();

        return new JsonResponse("Commande Enregistrer",Response::HTTP_CREATED,[],true);
        
    }

    
}
