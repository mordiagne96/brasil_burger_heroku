<?php
namespace App\Services;

use App\Entity\Produit;
use App\Repository\PortionFriteRepository;
use App\Repository\ProduitRepository;

final class ProduitExisteService{

    private ProduitRepository $repo;

    public function __construct(ProduitRepository $repo)
    {
        $this->repo = $repo;
    }

    public function isExist(Produit $data){
        $portion = $this->repo->find($data->getId());
        
        if($portion->getEtat() != "Disponible"){
            return false;
        }else{
            return true;
        }
    }

}