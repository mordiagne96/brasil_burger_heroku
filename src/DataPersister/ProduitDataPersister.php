<?php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\Burger;
use App\Entity\Produit;
use App\Services\DecodeService;
use App\Services\UploadService;
use App\Services\CalculePrixMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Services\ProduitExisteService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProduitDataPersister implements ContextAwareDataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private ?TokenInterface $token;
    private $service;
    private $uploadService;
    private $produitExisteService;

    public function __construct(TokenStorageInterface $tokenStorage,RequestStack $request,EntityManagerInterface $entityManager,CalculePrixMenuService $service,UploadService $uploadService,ProduitExisteService $produitExisteService)
    {
        $this->entityManager = $entityManager;
        $this->service = $service;
        $this->uploadService = $uploadService;
        $this->token = $tokenStorage->getToken();
        $this->produitExisteService = $produitExisteService;
    }

    public function supports($data, array $context = []): bool
    {
        // dd($data);
        return $data instanceof Produit;
    }

    public function persist($data, array $context = [])
    {
        if($data instanceof Menu){

           if(!$this->produitExisteService->isExist($data->getPortionFrite())){
                return new JsonResponse("Vous avez choisis une portion de frite qui n'est pas disponible" ,Response::HTTP_BAD_REQUEST,[],true);
           }

           foreach ($data->getBurgerMenus() as $burgerMenu) {
                if(!$this->produitExisteService->isExist($burgerMenu->getBurger())){
                    return new JsonResponse("Le Burger (".$burgerMenu->getBurger()->getNom()." ) n'est pas disponible" ,Response::HTTP_BAD_REQUEST,[],true);
                }
           }

           $data = $this->service->calculer($data);
           $data = $this->uploadService->upload($data);

        }

        if($data instanceof Burger){

            $data = $this->uploadService->upload($data);

        }

        $data->setGestionnaire($this->token->getUser());
        $data->setEtat("Disponible");
        // dd($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $data->setEtat("Archiver");
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}