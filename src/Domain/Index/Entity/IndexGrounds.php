<?php

namespace App\Domain\Index\Entity;

use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Repository\IndexGroundsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndexGroundsRepository::class)
 */
class IndexGrounds
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=75)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="string", length=75)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity=Ilots::class, mappedBy="type")
     */
    private $ilots;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $humus_mineralization;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nitrogen;
    
    public function __construct()
    {
        $this->ilots = new ArrayCollection();
    }
    
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
    
    /**
     * @return Collection|Ilots[]
     */
    public function getIlots(): Collection
    {
        return $this->ilots;
    }
    
    public function addIlot(Ilots $ilot): self
    {
        if(! $this->ilots->contains($ilot)) {
            $this->ilots[] = $ilot;
            $ilot->setType($this);
        }
        
        return $this;
    }
    
    public function removeIlot(Ilots $ilot): self
    {
        if($this->ilots->contains($ilot)) {
            $this->ilots->removeElement($ilot);
            // set the owning side to null (unless already changed)
            if($ilot->getType() === $this) {
                $ilot->setType(null);
            }
        }
        
        return $this;
    }
    
    public function getHumusMineralization(): ?float
    {
        return $this->humus_mineralization;
    }
    
    public function setHumusMineralization(?float $humus_mineralization): self
    {
        $this->humus_mineralization = $humus_mineralization;
        
        return $this;
    }
    
    public function getNitrogen(): ?float
    {
        return $this->nitrogen;
    }
    
    public function setNitrogen(?float $nitrogen): self
    {
        $this->nitrogen = $nitrogen;
        
        return $this;
    }
}
