<?php
// src/DataTransformer/BookInputDataTransformerInitializer.php

namespace App\DataTransformer;

use App\Entity\Book;
use App\Dto\BookInput;
use App\Entity\Quartier;
use App\Dto\QuartierInput;
use App\Repository\ZoneRepository;
use ApiPlatform\Core\Validator\ValidatorInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use App\Dto\ZoneInput;

final class QuartierInputDataTransformerInitializer implements DataTransformerInitializerInterface
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
    public function transform($data, string $to, array $context = [])
    {
        $existingQuartier = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        $existingQuartier->setLibelle($data->libelle);
        // $existingQuartier->setZone($this->repo->find($data->zone->id));
        // dd($existingQuartier);
        return $existingQuartier;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $inputClass, array $context = [])
    {
        
        $existingQuartier = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingQuartier) {
            return new QuartierInput();
        }

        // dd($existingQuartier->getZone()->getNom());
        $quartierInput = new QuartierInput();
        $zoneInput = new ZoneInput();
        $quartierInput->libelle = $existingQuartier->getLibelle();
        // dd($zoneInput->id);
        $zoneInput->id = $existingQuartier->getZone()->getId();
        $zoneInput->nom = $existingQuartier->getZone()->getNom();
        $zoneInput->prix = $existingQuartier->getZone()->getPrix();
        $quartierInput->zone = $zoneInput;
        // dd($quartierInput);
        return $quartierInput;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Quartier) {
          return false;
        }
        // if($context['operation_type'] == 'item'){
        //     return false;
        // }
        return Quartier::class === $to && null !== ($context['input']['class'] ?? null);
    }
}