<?php
// src/DataTransformer/BookOutputDataTransformer.php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\BookOutput;
use App\Dto\QuartierOutput;
use App\Dto\ZoneOutput;
use App\Entity\Book;
use App\Entity\Quartier;

final class QuartierOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        $quartierOutput = new QuartierOutput();
        $zoneOutput = new ZoneOutput();
        // dd($data->getId());
        $quartierOutput->id = $data->getId();
        $quartierOutput->libelle = $data->getLibelle();
        $zoneOutput->id = $data->getZone()->getId();
        $zoneOutput->nom = $data->getZone()->getNom();
        $zoneOutput->prix = $data->getZone()->getPrix();

        $quartierOutput->zone = $zoneOutput;
        // dd($quartierOutput);
        return $quartierOutput;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return QuartierOutput::class === $to && $data instanceof Quartier;
    }
}
