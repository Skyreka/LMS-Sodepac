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
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cultures", mappedBy="ilot")
     */
    private $cultures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Irrigation", mappedBy="ilot")
     */
    private $irrigations;

    public function __construct()
    {
        $this->cultures = new ArrayCollection();
        $this->irrigations = new ArrayCollection();
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

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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
}
