<?php

namespace App\Domain\Culture\Entity;

use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Entity\IndexEffluents;
use App\Domain\PPF\Entity\PPF;
use App\Entity\Interventions;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CulturesRepository::class)
 */
class Cultures
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Ilots::class, inversedBy="cultures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ilot;
    
    /**
     * @ORM\ManyToOne(targetEntity=IndexCultures::class, inversedBy="cultures", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $name;
    
    /**
     * @ORM\Column(type="float")
     */
    private $size;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity=IndexCultures::class)
     */
    private $precedent;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $residue;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $bio;
    
    /**
     * @ORM\ManyToOne(targetEntity=IndexEffluents::class)
     */
    private $effluent;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $production;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $znt;
    
    /**
     * @ORM\OneToMany(targetEntity=Interventions::class, mappedBy="culture", orphanRemoval=true)
     */
    private $interventions;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status = 0;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permanent;
    
    /**
     * @ORM\OneToMany(targetEntity=PPF::class, mappedBy="culture")
     */
    private $ppfs;
    
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
        $this->interventions = new ArrayCollection();
        $this->ppfs          = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getIlot(): ?Ilots
    {
        return $this->ilot;
    }
    
    public function setIlot(?Ilots $ilot): self
    {
        $this->ilot = $ilot;
        
        return $this;
    }
    
    public function getName(): ?IndexCultures
    {
        return $this->name;
    }
    
    public function setName(?IndexCultures $name): self
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
    
    public function getComments(): ?string
    {
        return $this->comments;
    }
    
    public function setComments(?string $comments): self
    {
        $this->comments = $comments;
        
        return $this;
    }
    
    public function getPrecedent(): ?IndexCultures
    {
        return $this->precedent;
    }
    
    public function setPrecedent(?IndexCultures $precedent): self
    {
        $this->precedent = $precedent;
        
        return $this;
    }
    
    public function getResidue(): ?bool
    {
        return $this->residue;
    }
    
    public function setResidue(?bool $residue): self
    {
        $this->residue = $residue;
        
        return $this;
    }
    
    public function getBio(): ?bool
    {
        return $this->bio;
    }
    
    public function setBio(bool $bio): self
    {
        $this->bio = $bio;
        
        return $this;
    }
    
    public function getEffluent(): ?IndexEffluents
    {
        return $this->effluent;
    }
    
    public function setEffluent(?IndexEffluents $effluent): self
    {
        $this->effluent = $effluent;
        
        return $this;
    }
    
    public function getProduction(): ?bool
    {
        return $this->production;
    }
    
    public function setProduction(bool $production): self
    {
        $this->production = $production;
        
        return $this;
    }
    
    public function getRealSize()
    {
        return $this->getSize() * $this->getZnt();
    }
    
    public function getZnt(): ?float
    {
        return $this->znt;
    }
    
    public function setZnt(?float $znt): self
    {
        $this->znt = $znt;
        
        return $this;
    }
    
    /**
     * @return Collection|Interventions[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }
    
    public function addIntervention(Interventions $intervention): self
    {
        if(! $this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setCulture($this);
        }
        
        return $this;
    }
    
    public function removeIntervention(Interventions $intervention): self
    {
        if($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            // set the owning side to null (unless already changed)
            if($intervention->getCulture() === $this) {
                $intervention->setCulture(null);
            }
        }
        
        return $this;
    }
    
    public function serialize()
    {
        return serialize([
            $this->id
        ]);
    }
    
    public function getStatus(): ?bool
    {
        return $this->status;
    }
    
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        
        return $this;
    }
    
    public function getPermanent(): ?bool
    {
        return $this->permanent;
    }
    
    public function setPermanent(?bool $permanent): self
    {
        $this->permanent = $permanent;
        
        return $this;
    }
    
    /**
     * @return Collection|PPF[]
     */
    public function getPpfs(): Collection
    {
        return $this->ppfs;
    }
    
    public function addPpf(PPF $ppf): self
    {
        if(! $this->ppfs->contains($ppf)) {
            $this->ppfs[] = $ppf;
            $ppf->setCulture($this);
        }
        
        return $this;
    }
    
    public function removePpf(PPF $ppf): self
    {
        if($this->ppfs->removeElement($ppf)) {
            // set the owning side to null (unless already changed)
            if($ppf->getCulture() === $this) {
                $ppf->setCulture(null);
            }
        }
        
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
