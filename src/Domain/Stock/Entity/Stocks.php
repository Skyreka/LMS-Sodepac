<?php

namespace App\Domain\Stock\Entity;

use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Product\Entity\Products;
use App\Domain\Stock\Repository\StocksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StocksRepository::class)
 */
class Stocks
{
    const UNIT = [
        '0' => 'Aucune unité',
        '1' => 'Litres',
        '2' => 'Kilos',
        '3' => 'Pack',
        null => ' '
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Exploitation::class, inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;
    
    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="stocks", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;
    
    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $unit;
    
    /**
     * @ORM\Column(type="float")
     */
    private $used_quantity = 0;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getExploitation(): ?Exploitation
    {
        return $this->exploitation;
    }
    
    public function setExploitation(?Exploitation $exploitation): self
    {
        $this->exploitation = $exploitation;
        
        return $this;
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
    
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }
    
    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;
        
        return $this;
    }
    
    public function getUnit($return = null): ?string
    {
        if($return) {
            return self::UNIT[$this->unit];
        }
        return $this->unit;
    }
    
    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;
        
        return $this;
    }
    
    public function getUsedQuantity(): ?float
    {
        return $this->used_quantity;
    }
    
    public function setUsedQuantity(float $used_quantity): self
    {
        $this->used_quantity = $used_quantity;
        
        return $this;
    }
}
