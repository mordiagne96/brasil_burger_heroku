<?php

namespace App\EventSubscriber;

use App\Entity\Burger;
use Doctrine\ORM\Events;
// use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class UserSubscriber implements EventSubscriberInterface
{
    private ?TokenInterface $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage->getToken();
    }   

    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }
    
    private function getUser()
    {
        //dd($this->token);
            if (null === $token = $this->token) {
            return null;
        }
        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }
        return $user;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Burger) {
            $args->getObject()->setGestionnaire($this->getUser());
        }
    }
}
