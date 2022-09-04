<?php
namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use App\Entity\Burger;
use App\Repository\BurgerRepository;

class BurgerDataProvider  implements RestrictedDataProviderInterface{

    private $burgerRepository;

    public function __construct(BurgerRepository $burgerRepository)
    {
        $this->burgerRepository = $burgerRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Burger::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->burgerRepository->findBy(["etat"=>0]);   
    }
}