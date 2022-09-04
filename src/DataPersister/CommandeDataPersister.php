<?php
namespace App\DataPersister;

use DateTime;
use App\Entity\Commande;
use App\Services\UpdateStockService;
use App\Repository\CommandeRepository;
use App\Services\ValidCommandeService;
use App\Services\CalculePrixMenuService;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\GenererNumeroCommandeService;
use App\Services\CalculeMontantCommandeService;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeDataPersister implements DataPersisterInterface
{
    private $entityManager;
    private $service;
    private $genererService;
    private $token;
    private $validCommandeService;
    private $updateStockService;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        CalculeMontantCommandeService $montantservice, 
        GenererNumeroCommandeService $genererService, 
        ValidCommandeService $validCommandeService,
        UpdateStockService $updateStockService)
    {
        $this->entityManager = $entityManager;
        $this->montantservice = $montantservice;
        $this->genererService = $genererService;
        $this->token = $tokenStorage->getToken();
        $this->validCommandeService = $validCommandeService;
        $this->UpdateStockService = $updateStockService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Commande;
    }

    public function persist($data, array $context = [])
    {
        $tailleBoissonUpdate = $this->validCommandeService->isValidCommande($data);

        $numero = $this->genererService->genererNumero();
        $data->setNumeroCommande($numero);
        $commande = $this->montantservice->calcule($data);
        $commande->setDate(new DateTime());
        $commande->setClient($this->token->getUser());

        $this->entityManager->persist($commande);
        $this->UpdateStockService->updateStock($tailleBoissonUpdate);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
    }
}