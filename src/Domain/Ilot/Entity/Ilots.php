<?php

namespace App\Domain\Ilot\Entity;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Ilot\Repository\IlotsRepository;
use App\Domain\Index\Entity\IndexGrounds;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IlotsRepository::class)
 */
class Ilots
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Exploitation::class, inversedBy="ilots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="float")
     */
    private $size;
    
    /**
     * @ORM\OneToMany(targetEntity=Cultures::class, mappedBy="ilot", orphanRemoval=true)
     */
    private $cultures;
    
    /**
     * @ORM\ManyToOne(targetEntity=IndexGrounds::class, inversedBy="ilots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $number;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $addedAt;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = 1;
    
    public function __construct()
    {
        $this->cultures = new ArrayCollection();
    }
    
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
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getSize(): ?float
    {
        return $this->size;
    }
    
    public function setSize(float $size): self
    {
        $this->size = $size;
        
        return $this;
    }
    
    /**
     * @return Collection|Cultures[]
     */
    public function getCultures(): Collection
    {
        return $this->cultures;
    }
    
    public function addCulture(Cultures $culture): self
    {
        if(! $this->cultures->contains($culture)) {
            $this->cultures[] = $culture;
            $culture->setIlot($this);
        }
        
        return $this;
    }
    
    public function removeCulture(Cultures $culture): self
    {
        if($this->cultures->contains($culture)) {
            $this->cultures->removeElement($culture);
            // set the owning side to null (unless already changed)
            if($culture->getIlot() === $this) {
                $culture->setIlot(null);
            }
        }
        
        return $this;
    }
    
    public function getType(): ?IndexGrounds
    {
        return $this->type;
    }
    
    public function setType(?IndexGrounds $type): self
    {
        $this->type = $type;
        
        return $this;
    }
    
    public function getNumber(): ?float
    {
        return $this->number;
    }
    
    public function setNumber(?float $number): self
    {
        $this->number = $number;
        
        return $this;
    }
    
    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }
    
    public function setAddedAt(?\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;
        
        return $this;
    }
    
    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }
    
    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;
        
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
