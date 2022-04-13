<?php

namespace App\Domain\Index\Entity;

use App\Domain\Index\Repository\IndexEffluentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndexEffluentsRepository::class)
 */
class IndexEffluents
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nitrogen_content;
    
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
    
    public function getNitrogenContent(): ?float
    {
        return $this->nitrogen_content;
    }
    
    public function setNitrogenContent(?float $nitrogen_content): self
    {
        $this->nitrogen_content = $nitrogen_content;
        
        return $this;
    }
}
