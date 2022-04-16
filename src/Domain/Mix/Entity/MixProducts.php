<?php

namespace App\Domain\Mix\Entity;

use App\Domain\Mix\Repository\MixProductsRepository;
use App\Domain\Product\Entity\Products;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MixProductsRepository::class)
 */
class MixProducts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity=Mix::class, inversedBy="mixProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mix;
    
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
    
    public function getMix(): ?Mix
    {
        return $this->mix;
    }
    
    public function setMix(?Mix $mix): self
    {
        $this->mix = $mix;
        
        return $this;
    }
}
