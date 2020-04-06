<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecommendationProductsRepository")
 */
class RecommendationProducts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dose;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityUnit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recommendations", inversedBy="recommendationProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recommendation;

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

    public function getDose(): ?float
    {
        return $this->dose;
    }

    public function setDose(?float $dose): self
    {
        $this->dose = $dose;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityUnit(): ?int
    {
        return $this->quantityUnit;
    }

    public function setQuantityUnit(?int $quantityUnit): self
    {
        $this->quantityUnit = $quantityUnit;

        return $this;
    }

    public function getRecommendation(): ?Recommendations
    {
        return $this->recommendation;
    }

    public function setRecommendation(?Recommendations $recommendation): self
    {
        $this->recommendation = $recommendation;

        return $this;
    }
}
