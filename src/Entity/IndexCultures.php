<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexCulturesRepository")
 * @UniqueEntity("name")
 */
class IndexCultures
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permanent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cultures", mappedBy="name")
     */
    private $cultures;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_lex;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplay;

    /**
     * @ORM\OneToMany(targetEntity=Doses::class, mappedBy="indexCulture")
     */
    private $doses;

    public function __construct()
    {
        $this->cultures = new ArrayCollection();
        $this->doses = new ArrayCollection();
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
            $culture->setName($this);
        }

        return $this;
    }

    public function removeCulture(Cultures $culture): self
    {
        if ($this->cultures->contains($culture)) {
            $this->cultures->removeElement($culture);
            // set the owning side to null (unless already changed)
            if ($culture->getName() === $this) {
                $culture->setName(null);
            }
        }

        return $this;
    }

    public function getIdLex(): ?int
    {
        return $this->id_lex;
    }

    public function setIdLex(?int $id_lex): self
    {
        $this->id_lex = $id_lex;

        return $this;
    }

    public function getIsDisplay(): ?bool
    {
        return $this->isDisplay;
    }

    public function setIsDisplay(bool $isDisplay): self
    {
        $this->isDisplay = $isDisplay;

        return $this;
    }

    /**
     * @return Collection|Doses[]
     */
    public function getDoses(): Collection
    {
        return $this->doses;
    }

    public function addDose(Doses $dose): self
    {
        if (!$this->doses->contains($dose)) {
            $this->doses[] = $dose;
            $dose->setIndexCulture($this);
        }

        return $this;
    }

    public function removeDose(Doses $dose): self
    {
        if ($this->doses->removeElement($dose)) {
            // set the owning side to null (unless already changed)
            if ($dose->getIndexCulture() === $this) {
                $dose->setIndexCulture(null);
            }
        }

        return $this;
    }
}
