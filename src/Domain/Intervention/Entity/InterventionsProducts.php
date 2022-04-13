<?php

namespace App\Entity;

use App\Domain\Intervention\Repository\InterventionsProductsRepository;
use App\Domain\Product\Entity\Products;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterventionsProductsRepository::class)
 */
class InterventionsProducts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity=Phyto::class, inversedBy="interventionsProducts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $intervention;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dose;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $doseHectare;
    
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
    
    public function getIntervention(): ?Interventions
    {
        return $this->intervention;
    }
    
    public function setIntervention(?Interventions $intervention): self
    {
        $this->intervention = $intervention;
        
        return $this;
    }
    
    public function getDose(): ?float
    {
        return $this->dose;
    }
    
    public function setDose($dose): void
    {
        $this->dose = $dose;
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
    
    /**
     * Function to get IFT
     * @return string
     */
    public function getIft()
    {
        // IF MAKE CHANGE HERE CHANGE ON INTERVENTIONPRODUCT
        $doseHomologue = $this->getDose();
        $doseHectare   = $this->getDoseHectare();
        
        //-- Display only if have all value
        if($doseHomologue != null &&
            $doseHectare != null) {
            $result = ($doseHectare / $doseHomologue);
            return $result;
        } else {
            return 0;
        }
    }
    
    public function getDoseHectare(): ?float
    {
        return $this->doseHectare;
    }
    
    public function setDoseHectare(?float $doseHectare): self
    {
        $this->doseHectare = $doseHectare;
        
        return $this;
    }
}
