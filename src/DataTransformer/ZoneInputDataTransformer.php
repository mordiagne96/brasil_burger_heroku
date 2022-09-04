<?php
// src/DataTransformer/BookInputDataTransformer.php

namespace App\DataTransformer;

use App\Entity\Book;
use App\Entity\Zone;
use ApiPlatform\Core\Validator\ValidatorInterface;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Quartier;

final class ZoneInputDataTransformer implements DataTransformerInterface
{
    private $validator;
    
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []): Zone
    {
        $this->validator->validate($data);
        
        $zone = new Zone();
        $zone->setNom($data->nom);
        $zone->setPrix($data->prix);

        // dd($data->quartiers);

        foreach ($data->quartiers as $q) {
            $quartier = new Quartier();
            $quartier->setLibelle($q->libelle);
            $zone->addQuartier($quartier);
            // dump($quartier->libelle);
        }

        // dd($zone);
        return $zone;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Zone) {
          return false;
        }

        return Zone::class === $to && null !== ($context['input']['class'] ?? null);
    }
}