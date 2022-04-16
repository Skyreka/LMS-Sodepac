<?php

namespace App\Domain\Recommendation\Entity;

use App\Domain\Product\Entity\Products;
use App\Domain\Recommendation\Repository\RecommendationProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecommendationProductsRepository::class)
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
     * @ORM\ManyToOne(targetEntity=Products::class, fetch="EAGER")
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
     * @ORM\ManyToOne(targetEntity=Recommendations::class, inversedBy="recommendationProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recommendation;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dose_edit;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $c_id;
    
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
    
    public function getDoseEdit(): ?float
    {
        return $this->dose_edit;
    }
    
    public function setDoseEdit(?float $dose_edit): self
    {
        $this->dose_edit = $dose_edit;
        
        return $this;
    }
    
    public function getCId(): ?string
    {
        return $this->c_id;
    }
    
    public function setCId(?string $c_id): self
    {
        $this->c_id = $c_id;
        
        return $this;
    }
}
