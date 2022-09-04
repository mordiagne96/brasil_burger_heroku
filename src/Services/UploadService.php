<?php

namespace App\Services;

use App\Entity\Burger;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UploadService
{
    private ?TokenInterface $token;
    public function __construct(RequestStack $requestStack, TokenStorageInterface $tokenStorage) {
        $this->requestStack = $requestStack;
        $this->token = $tokenStorage->getToken();
    }

    public function upload(Produit $produit)
    {
        // dd($produit->getFile());
        $image = stream_get_contents(fopen($produit->getFile()->getRealPath(), 'rb'));
        // dd($menu);
           $produit->setImage($image);
        return $produit;
    }

}