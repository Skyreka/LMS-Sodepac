<?php

namespace App\Domain\Index\Entity;

use App\Domain\Index\Repository\IndexCanevasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndexCanevasRepository::class)
 */
class IndexCanevas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = 1;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
    
    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        
        return $this;
    }
}
