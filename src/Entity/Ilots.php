<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IlotsRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Exploitation", inversedBy="ilots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $size;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cultures", mappedBy="ilot", orphanRemoval=true)
     */
    private $cultures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Irrigation", mappedBy="ilot")
     */
    private $irrigations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Analyse", mappedBy="ilot", orphanRemoval=true)
     */
    private $analyses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexGrounds", inversedBy="ilots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $number;

    public function __construct()
    {
        $this->cultures = new ArrayCollection();
        $this->irrigations = new ArrayCollection();
        $this->analyses = new ArrayCollection();
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
        if (!$this->cultures->contains($culture)) {
            $this->cultures[] = $culture;
            $culture->setIlot($this);
        }

        return $this;
    }

    public function removeCulture(Cultures $culture): self
    {
        if ($this->cultures->contains($culture)) {
            $this->cultures->removeElement($culture);
            // set the owning side to null (unless already changed)
            if ($culture->getIlot() === $this) {
                $culture->setIlot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Irrigation[]
     */
    public function getIrrigations(): Collection
    {
        return $this->irrigations;
    }

    public function addIrrigation(Irrigation $irrigation): self
    {
        if (!$this->irrigations->contains($irrigation)) {
            $this->irrigations[] = $irrigation;
            $irrigation->setIlot($this);
        }

        return $this;
    }

    public function removeIrrigation(Irrigation $irrigation): self
    {
        if ($this->irrigations->contains($irrigation)) {
            $this->irrigations->removeElement($irrigation);
            // set the owning side to null (unless already changed)
            if ($irrigation->getIlot() === $this) {
                $irrigation->setIlot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Analyse[]
     */
    public function getAnalyses(): Collection
    {
        return $this->analyses;
    }

    public function addAnalysis(Analyse $analysis): self
    {
        if (!$this->analyses->contains($analysis)) {
            $this->analyses[] = $analysis;
            $analysis->setIlot($this);
        }

        return $this;
    }

    public function removeAnalysis(Analyse $analysis): self
    {
        if ($this->analyses->contains($analysis)) {
            $this->analyses->removeElement($analysis);
            // set the owning side to null (unless already changed)
            if ($analysis->getIlot() === $this) {
                $analysis->setIlot(null);
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
}
