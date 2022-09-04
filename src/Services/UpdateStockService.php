<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TailleBoissonRepository;

final class UpdateStockService{

    private TailleBoissonRepository $repo;
    private EntityManagerInterface $entityManager;

    public function __construct(TailleBoissonRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    public function updateStock(Array $array){

        foreach ($array as $id => $quantite) {

            $tailleBoisson = $this->repo->find($id);
            $stock = $tailleBoisson->getQuantite() - $quantite;
            $tailleBoisson ->setQuantite($stock);
            // return $tailleBoisson;
            $this->entityManager->persist($tailleBoisson);
        }

    }

}