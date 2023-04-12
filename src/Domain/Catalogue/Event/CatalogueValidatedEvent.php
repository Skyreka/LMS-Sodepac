<?php

namespace App\Domain\Catalogue\Event;

use App\Domain\Catalogue\Entity\Catalogue;

class CatalogueValidatedEvent
{
    public function __construct(private readonly Catalogue $catalogue)
    {
    }
    
    public function getCatalogue(): Catalogue
    {
        return $this->catalogue;
    }
}
