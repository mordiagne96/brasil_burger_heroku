<?php

namespace App\EventSubscriber;

use App\Entity\Menu;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MenuSubscriber implements EventSubscriberInterface
{
    
    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $menu)
    {       
        // $boisson->getObject()->setPrix("1400");

        if ($menu->getObject() instanceof Menu) {
            $burgers= $menu->getObject()->getBurgers();
            $taille = $menu->getObject()->getTaille();
            $portion = $menu->getObject()->getPortionFrite();

            $prix = 0;

            foreach ($burgers as $burger) {
                $prix = $prix + $burger->getPrix();
            }
            $prix = $prix + $taille->getPrix();
            $prix = $prix + $portion->getPrix();

            $menu->getObject()->setprix($prix);
        }
    }
}
