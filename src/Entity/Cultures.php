<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CulturesRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Ilots", inversedBy="cultures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ilot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexCultures", inversedBy="cultures")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexCultures")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexEffluents")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Interventions", mappedBy="culture", orphanRemoval=true)
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

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
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
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setCulture($this);
        }

        return $this;
    }

    public function removeIntervention(Interventions $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            // set the owning side to null (unless already changed)
            if ($intervention->getCulture() === $this) {
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
}
