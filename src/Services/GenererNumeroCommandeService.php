<?php
namespace App\Services;

use App\Repository\CommandeRepository;

final class GenererNumeroCommandeService{

    private CommandeRepository $repo;

    public function __construct(CommandeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function genererNumero(){

        $id = $this->repo->findOneBy([],['id'=>'desc'])->getId();
        $id++;
        $numero = "COM-00".$id;

        return $numero;

    }
}