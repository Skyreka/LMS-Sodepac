<?php

namespace App\Domain\Recommendation\Event;

use App\Domain\Recommendation\Entity\Recommendations;

class RecommendationValidatedEvent
{
    public function __construct(private readonly Recommendations $recommendations)
    {
    }
    
    /**
     * @return Recommendations
     */
    public function getRecommendations(): Recommendations
    {
        return $this->recommendations;
    }
}
