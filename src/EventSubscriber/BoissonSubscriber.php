<?php

namespace App\EventSubscriber;

use App\Entity\Boisson;
use Doctrine\ORM\Events;
use App\Repository\BoissonRepository;
use App\Repository\TailleRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BoissonSubscriber implements EventSubscriberInterface
{
    private TailleRepository $repoTaille;

    public function __construct(TailleRepository $repo)
    {
        $this->repoTaille = $repo;
    } 
    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $boisson)
    {     
        // $boisson->getObject()->setPrix("1400");

        if ($boisson->getObject() instanceof Boisson) {
            $tailles = $this->repoTaille->findAll();   

            foreach ($tailles as $taille) {
                $boisson->getObject()->addTaille($taille);
            }
        }

        // $args->getObject()->setPrix();
    }
}
