<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer")
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

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
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

    public function getZnt(): ?float
    {
        return $this->znt;
    }

    public function setZnt(?float $znt): self
    {
        $this->znt = $znt;

        return $this;
    }
}
