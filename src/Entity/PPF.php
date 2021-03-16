<?php

namespace App\Entity;

use App\Repository\PPFRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PPFRepository::class)
 */
class PPF
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cultures::class, inversedBy="ppfs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $culture;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $effiency_prev;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qty_azote_add_prev;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_implantation_planned;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $intermediate_culture;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $push_back;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_sow;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type_destruction;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qty_water_prev;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type_effluent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qty_ependu;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_spreading;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_destruction;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $remainder_soil_sow;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $resource_nitrate_content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCulture(): ?Cultures
    {
        return $this->culture;
    }

    public function setCulture(?Cultures $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getEffiencyPrev(): ?float
    {
        return $this->effiency_prev;
    }

    public function setEffiencyPrev(?float $effiency_prev): self
    {
        $this->effiency_prev = $effiency_prev;

        return $this;
    }

    public function getQtyAzoteAddPrev(): ?float
    {
        return $this->qty_azote_add_prev;
    }

    public function setQtyAzoteAddPrev(?float $qty_azote_add_prev): self
    {
        $this->qty_azote_add_prev = $qty_azote_add_prev;

        return $this;
    }

    public function getDateImplantationPlanned(): ?\DateTimeInterface
    {
        return $this->date_implantation_planned;
    }

    public function setDateImplantationPlanned(?\DateTimeInterface $date_implantation_planned): self
    {
        $this->date_implantation_planned = $date_implantation_planned;

        return $this;
    }

    public function getIntermediateCulture(): ?bool
    {
        return $this->intermediate_culture;
    }

    public function setIntermediateCulture(?bool $intermediate_culture): self
    {
        $this->intermediate_culture = $intermediate_culture;

        return $this;
    }

    public function getPushBack(): ?bool
    {
        return $this->push_back;
    }

    public function setPushBack(?bool $push_back): self
    {
        $this->push_back = $push_back;

        return $this;
    }

    public function getDateSow(): ?\DateTimeInterface
    {
        return $this->date_sow;
    }

    public function setDateSow(?\DateTimeInterface $date_sow): self
    {
        $this->date_sow = $date_sow;

        return $this;
    }

    public function getTypeDestruction(): ?string
    {
        return $this->type_destruction;
    }

    public function setTypeDestruction(?string $type_destruction): self
    {
        $this->type_destruction = $type_destruction;

        return $this;
    }

    public function getQtyWaterPrev(): ?float
    {
        return $this->qty_water_prev;
    }

    public function setQtyWaterPrev(?float $qty_water_prev): self
    {
        $this->qty_water_prev = $qty_water_prev;

        return $this;
    }

    public function getTypeEffluent(): ?int
    {
        return $this->type_effluent;
    }

    public function setTypeEffluent(?int $type_effluent): self
    {
        $this->type_effluent = $type_effluent;

        return $this;
    }

    public function getQtyEpendu(): ?float
    {
        return $this->qty_ependu;
    }

    public function setQtyEpendu(?float $qty_ependu): self
    {
        $this->qty_ependu = $qty_ependu;

        return $this;
    }

    public function getDateSpreading(): ?\DateTimeInterface
    {
        return $this->date_spreading;
    }

    public function setDateSpreading(?\DateTimeInterface $date_spreading): self
    {
        $this->date_spreading = $date_spreading;

        return $this;
    }

    public function getDateDestruction(): ?\DateTimeInterface
    {
        return $this->date_destruction;
    }

    public function setDateDestruction(?\DateTimeInterface $date_destruction): self
    {
        $this->date_destruction = $date_destruction;

        return $this;
    }

    public function getRemainderSoilSow(): ?float
    {
        return $this->remainder_soil_sow;
    }

    public function setRemainderSoilSow(?float $remainder_soil_sow): self
    {
        $this->remainder_soil_sow = $remainder_soil_sow;

        return $this;
    }

    public function getResourceNitrateContent(): ?float
    {
        return $this->resource_nitrate_content;
    }

    public function setResourceNitrateContent(?float $resource_nitrate_content): self
    {
        $this->resource_nitrate_content = $resource_nitrate_content;

        return $this;
    }
}
