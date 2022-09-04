<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Services\GenererTicketService;
use App\Services\SetGestionnaireService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;

class UpdateController extends AbstractController
{
    #[Route('/updateEtat/{id}/{etat}', name: 'update_commande_etat')]
    public function __invoke(CommandeRepository $repo, $id, $etat, EntityManagerInterface $entityManager, GenererTicketService $ticketService, SetGestionnaireService $setGestService)
    {
        $ancienneEtat = "";
        $commande = $repo->find($id);
        $ticketCommande = [];
        if($commande != null){
            $ancienneEtat = $commande->getEtat();
            switch (strtolower($etat)) {
                case 'terminer':
                    if($ancienneEtat == "en cours"){

                        $commande->setEtat("terminer");
                        $ticketCommande = $ticketService->genererTicket($commande);
                        // $commande = $setGestService->setGest($commande);
                        // dd($commande->getGestionnaire());
                    }else{
                        return new JsonResponse("Impossible de passer l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_BAD_REQUEST,[],true);
                    }
                    break;
                case 'en livraison':
                    if($ancienneEtat == "terminer"){
                        $commande->setEtat("en livraison");
                    }else{
                        return new JsonResponse("Impossible de passer l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_BAD_REQUEST,[],true);
                    }
                    break;
                case 'valider':
                    if($ancienneEtat == "terminer" && $commande->getQuartier() == null || $ancienneEtat == "en livraison"){
                        $commande->setEtat("valider");
                        $dompdf = new Dompdf();
                        $dompdf->loadHtml('hello world');

                        // (Optional) Setup the paper size and orientation
                        $dompdf->setPaper('A4', 'landscape');

                        // Render the HTML as PDF
                        $dompdf->render();

                        // Output the generated PDF to Browser
                        $dompdf->stream();
                    }else{
                        return new JsonResponse("Impossible de passer l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_BAD_REQUEST,[],true);
                    }
                    break;
                case 'annuler':
                    if($ancienneEtat == "terminer" || $ancienneEtat == "en livraison" || $ancienneEtat == "en cours"){
                        $commande->setEtat("annuler");
                    }else{
                        return new JsonResponse("Impossible de passer l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_BAD_REQUEST,[],true);
                    }
                    break;
                case 'en cours':
                        return new JsonResponse("Impossible de passer l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_BAD_REQUEST,[],true);
                    break;
                default:
                        return new JsonResponse("Cette etat n'existe pas!!" ,Response::HTTP_BAD_REQUEST,[],true);
                    break;
            }

        }else{
            return new JsonResponse("Lacours Commande que vous voulez modifier n'existe pas!! " ,Response::HTTP_BAD_REQUEST,[],true);
        }

        $entityManager->persist($commande);
        $entityManager->flush();

        if($etat == "terminer"){
            return new JsonResponse($ticketCommande ,Response::HTTP_OK,[]);
        }else{
            return new JsonResponse("Success: La commande passe de l'etat ( ".$ancienneEtat.") à l'etat (".$etat.")" ,Response::HTTP_OK,[],true);
        }       
    }

}
