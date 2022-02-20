<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RiskPhaseRepository")
 */
class RiskPhase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products", inversedBy="riskPhases")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $long_wording;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_wording;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getLongWording(): ?string
    {
        return $this->long_wording;
    }

    public function setLongWording(string $long_wording): self
    {
        $this->long_wording = $long_wording;

        return $this;
    }

    public function getShortWording(): ?string
    {
        return $this->short_wording;
    }

    public function setShortWording(string $short_wording): self
    {
        $this->short_wording = $short_wording;

        return $this;
    }
}
