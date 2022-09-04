<?php

namespace App\DataProvider;

use App\Entity\Complement;
use App\Repository\BoissonRepository;
use App\Repository\PortionFriteRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderTrait;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class ComplementDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface, SerializerAwareDataProviderInterface{
    
    private $repoBoisson;
    private $repoPortion;
    use SerializerAwareDataProviderTrait;
    public function __construct(BoissonRepository $repoBoisson, PortionFriteRepository $repoPortion)
    {
        $this->repoBoisson = $repoBoisson;
        $this->repoPortion = $repoPortion;
    }


    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Complement::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $complement = [];
        $boissons = $this->repoBoisson->findBy(["etat"=>0]); 
        $portions = $this->repoPortion->findBy(["etat"=>0]); 
        $complement['tailles'] = $boissons;
        $complement['protions'] = $portions;

        return $complement;

    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ? Complement
    {
        $complement = new Complement();
        $boissons = $this->repoBoisson->findBy(["etat"=>0]); 
        $portions = $this->repoPortion->findBy(["etat"=>0]); 
        $complement->setTaille($boissons);
        $complement->setPortionFrite($portions);
        // dd("ok");
        return $complement;
    }
}