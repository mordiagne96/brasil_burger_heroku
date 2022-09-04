<?php
namespace App\DataProvider;

use App\Entity\Burger;
use App\Entity\Catalogue;
use App\Entity\Cataloguee;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class CatalogueDataProvider  implements ContextAwareCollectionDataProviderInterface,ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private $burgerRepo;
    private $menuRepo;

    public function __construct(BurgerRepository $burgerRepo, MenuRepository $menuRepo)
    {
        $this->burgerRepo = $burgerRepo;
        $this->menuRepo = $menuRepo;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Cataloguee::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {

        dd("onk");
        $catalogues = [];
        $burgers = $this->burgerRepo->findBy(["etat"=>0]);
        $menus = $this->menuRepo->findBy(["etat"=>0]);
        $catalogues=[
            ["burgers"=>$burgers],
            ["menu"=>$menus]
        ];
        
        return $catalogues;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []):Cataloguee
    {
        // dd("ok");
        // Retrieve the blog post item from somewhere then return it or null if not found
        $catalogue = new Cataloguee();
        $burgers = $this->burgerRepo->findBy(["etat"=>"Disponible"]);
        $menus = $this->menuRepo->findBy(["etat"=>"Disponible"]);
        $catalogue->setBurgers($burgers);
        $catalogue->setMenus($menus);
        // dd($catalogue);
        return $catalogue;
    }
}