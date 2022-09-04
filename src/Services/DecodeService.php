<?php

namespace App\Services;

use App\Entity\Burger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class DecodeService
{
    private ?TokenInterface $token;
    public function __construct(RequestStack $requestStack, TokenStorageInterface $tokenStorage) {
        $this->requestStack = $requestStack;
        $this->token = $tokenStorage->getToken();
    }

    public function decode(Request $request)
    {
        // dd($request->files);
        // $request = $event->getRequest('requestStack')->request;
        // $file = $event->getRequest('requestStack')->files;

        $files = $request->files; 

        // dd($files);
        
        $test = stream_get_contents(fopen($files->get('image')->getRealPath(), 'rb'));

        $burger = new Burger;
        $burger->setNom($request->get('nom'));
        $burger->setImage($test);
        $burger->setEtat($request->get('etat'));
        $burger->setPrix($request->get('prix'));
        $burger->setGestionnaire($this->token->getUser());
        // dd($burger);
        return $burger;
    }

    
}