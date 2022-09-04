<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Services\DecodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BurgerController extends AbstractController
{
    public function __invoke(Request $request,DecodeService $service,EntityManagerInterface $entityManager)
    {
        $burger = $service->decode($request);
        // dd($burger);
        $entityManager->persist($burger);
        $entityManager->flush();
    }
}
 