<?php
namespace App\DataPersister;

use App\Entity\Livraison;
use App\Services\CalculeMontantLivraisonService;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class LivraisonDataPersister implements DataPersisterInterface{

    private CalculeMontantLivraisonService $calculeMontantService;
    private EntityManagerInterface $entityManager;

    public function __construct(CalculeMontantLivraisonService $calculeMontantService,EntityManagerInterface $entityManager)
    {
        $this->calculeMontantService = $calculeMontantService;
        $this->entityManager = $entityManager;
    }

    public function supports($data,array $context = []): bool
    {
        return $data instanceof Livraison;
    }

    public function persist($data,array $context = [])
    {
        $data = $this->calculeMontantService->calculeMontantTotal($data);
        dd($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

    }

    public function remove($data,array $context = [])
    {
        
    }
}