<?php
// src/DataTransformer/BookInputDataTransformer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Book;
use App\Entity\Quartier;
use App\Repository\ZoneRepository;

final class QuartierInputDataTransformer implements DataTransformerInterface
{
    private $validator;
    private $repo;
    // private $repo;
    
    public function __construct(ValidatorInterface $validator, ZoneRepository $repo)
    {
        $this->validator = $validator;
        $this->repo = $repo;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []): Quartier
    {
        $this->validator->validate($data);
        // dd($this->repo->find($data->zone->id));

        $quartier = new Quartier();
        $quartier->setLibelle($data->libelle);
        $quartier->setZone($this->repo->find($data->zone->id));
        
        return $quartier;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // dump($data);
        if ($data instanceof Quartier) {
          return false;
        }

        if($context['operation_type'] == 'item'){
            return false;
        }

        return Quartier::class === $to && null !== ($context['input']['class'] ?? null);
    }
}