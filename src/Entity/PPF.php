<?php

namespace App\Entity;

use App\Repository\PPFRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PPFRepository::class)
 */
class PPF
{
    const STATUS = [
        1 => 'En cours',
        2 => 'Terminé'
    ];

    const TYPES = [
        1 => 'Tournesol',
        2 => 'Maîs-Sorgho'
    ];

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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient_equivalence;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qty_azote_add;

    /**
     * @ORM\OneToMany(targetEntity=PPFInput::class, mappedBy="ppf", orphanRemoval=true)
     */
    private $inputs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $added_date;

    /**
     * @ORM\ManyToOne(targetEntity=IndexEffluents::class)
     */
    private $effluent;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $needPlant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nitrogen_requirement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $effect_meadow;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $effect_residual_collected;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient_multiple;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient_use;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nutrigen_organic;

    public function __construct()
    {
        $this->inputs = new ArrayCollection();
    }

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

    public function getCoefficientEquivalence(): ?float
    {
        return $this->coefficient_equivalence;
    }

    public function setCoefficientEquivalence(?float $coefficient_equivalence): self
    {
        $this->coefficient_equivalence = $coefficient_equivalence;

        return $this;
    }

    public function getQtyAzoteAdd(): ?float
    {
        return $this->qty_azote_add;
    }

    public function setQtyAzoteAdd(?float $qty_azote_add): self
    {
        $this->qty_azote_add = $qty_azote_add;

        return $this;
    }

    /**
     * @return Collection|PPFInput[]
     */
    public function getInputs(): Collection
    {
        return $this->inputs;
    }

    public function addInput(PPFInput $input): self
    {
        if (!$this->inputs->contains($input)) {
            $this->inputs[] = $input;
            $input->setPpf($this);
        }

        return $this;
    }

    public function removeInput(PPFInput $input): self
    {
        if ($this->inputs->removeElement($input)) {
            // set the owning side to null (unless already changed)
            if ($input->getPpf() === $this) {
                $input->setPpf(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(?\DateTimeInterface $added_date): self
    {
        $this->added_date = $added_date;

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

    public function getType( $return = false ): string
    {
        if ($return) {
            return self::TYPES[$this->type];
        }
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNeedPlant(): ?float
    {
        return $this->needPlant;
    }

    public function setNeedPlant(?float $needPlant): self
    {
        $this->needPlant = $needPlant;

        return $this;
    }

    public function getNitrogenRequirement(): ?float
    {
        return $this->nitrogen_requirement;
    }

    public function setNitrogenRequirement(?float $nitrogen_requirement): self
    {
        $this->nitrogen_requirement = $nitrogen_requirement;

        return $this;
    }

    public function getEffectMeadow(): ?float
    {
        return $this->effect_meadow;
    }

    public function setEffectMeadow(?float $effect_meadow): self
    {
        $this->effect_meadow = $effect_meadow;

        return $this;
    }

    public function getEffectResidualCollected(): ?float
    {
        return $this->effect_residual_collected;
    }

    public function setEffectResidualCollected(?float $effect_residual_collected): self
    {
        $this->effect_residual_collected = $effect_residual_collected;

        return $this;
    }

    public function getCoefficientMultiple(): ?float
    {
        return $this->coefficient_multiple;
    }

    public function setCoefficientMultiple(?float $coefficient_multiple): self
    {
        $this->coefficient_multiple = $coefficient_multiple;

        return $this;
    }

    public function getCoefficientUse(): ?float
    {
        return $this->coefficient_use;
    }

    public function setCoefficientUse(?float $coefficient_use): self
    {
        $this->coefficient_use = $coefficient_use;

        return $this;
    }

    public function getNutrigenOrganic(): ?float
    {
        return $this->nutrigen_organic;
    }

    public function setNutrigenOrganic(?float $nutrigen_organic): self
    {
        $this->nutrigen_organic = $nutrigen_organic;

        return $this;
    }
}
