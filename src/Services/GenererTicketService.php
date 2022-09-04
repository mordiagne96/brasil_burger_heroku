<?php

namespace App\Services;

use App\Entity\Commande;
use App\Entity\Ticket;
use App\Repository\TicketRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

final class GenererTicketService{

    private TicketRepository $repo;
    private EntityManagerInterface $entityManager;

    public function __construct(TicketRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    public function genererTicket(Commande $commande){

        $id = 1;  $ticketCommande = [];

        if($this->repo->findOneBy([],['id'=>'desc']) != null){
            $id = $this->repo->findOneBy([],['id'=>'desc'])->getId();
            $id++;
        }

        $numero = "T-00".$id;
        $ticket = new Ticket();

        $ticket->setDate(new DateTime());
        $ticket->setNumero($numero);
        $ticket->setCommande($commande);

        $this->entityManager->persist($ticket);

        $ticketCommande["numero"] = $ticket->getNumero();
        $ticketCommande["date"] = $ticket->getDate()->format("d-m-Y H:i:s");
        $ticketCommande["montant"] = $ticket->getCommande()->getMontant();

        foreach ($ticket->getCommande()->getMenuCommandeTailleBoissons() as $menuComTailBois) {
            $ticketCommande["Menu"][]=[
                "nom" => $menuComTailBois->getMenu()->getNom(),
                "prix" => $menuComTailBois->getMenu()->getPrix()
            ];
        }
        foreach ($ticket->getCommande()->getBurgerCommandes() as $burgerCom) {
            $ticketCommande["Burger"][]=[
                "nom" => $burgerCom->getBurger()->getNom(),
                "prix" => $burgerCom->getBurger()->getPrix()
            ];
        }
        
        return $ticketCommande;
    }

}