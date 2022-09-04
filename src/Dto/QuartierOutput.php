<?php

namespace App\Dto;

use App\Dto\ZoneOutput;

final class QuartierOutput
{
    /**
     * @var int
     */
    public $id;
    
    /**
    * @var string
    */
    public $libelle;

    /**
     * @var ZoneOutput
     */
    public $zone;
}