<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Products
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stocks", mappedBy="product", orphanRemoval=true)
     */
    private $stocks;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RiskPhase", mappedBy="product")
     */
    private $riskPhases;

    /**
     * @ORM\Column(type="boolean")
     */
    private $private = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_lex;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $substance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tox;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $risk_phase;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $znt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $security_mention;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $danger_mention;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $warning_mention;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->riskPhases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Stocks[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stocks $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduct($this);
        }

        return $this;
    }

    public function removeStock(Stocks $stock): self
    {
        if ($this->stocks->contains($stock)) {
            $this->stocks->removeElement($stock);
            // set the owning side to null (unless already changed)
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getId();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|RiskPhase[]
     */
    public function getRiskPhases(): Collection
    {
        return $this->riskPhases;
    }

    public function addRiskPhase(RiskPhase $riskPhase): self
    {
        if (!$this->riskPhases->contains($riskPhase)) {
            $this->riskPhases[] = $riskPhase;
            $riskPhase->setProduct($this);
        }

        return $this;
    }

    public function removeRiskPhase(RiskPhase $riskPhase): self
    {
        if ($this->riskPhases->contains($riskPhase)) {
            $this->riskPhases->removeElement($riskPhase);
            // set the owning side to null (unless already changed)
            if ($riskPhase->getProduct() === $this) {
                $riskPhase->setProduct(null);
            }
        }

        return $this;
    }

    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getIdLex(): ?string
    {
        return $this->id_lex;
    }

    public function setIdLex(?string $id_lex): self
    {
        $this->id_lex = $id_lex;

        return $this;
    }

    public function getSubstance(): ?string
    {
        return $this->substance;
    }

    public function setSubstance(?string $substance): self
    {
        $this->substance = $substance;

        return $this;
    }

    public function getTox(): ?string
    {
        return $this->tox;
    }

    public function setTox(?string $tox): self
    {
        $this->tox = $tox;

        return $this;
    }

    public function getRiskPhase(): ?string
    {
        return $this->risk_phase;
    }

    public function setRiskPhase(?string $risk_phase): self
    {
        $this->risk_phase = $risk_phase;

        return $this;
    }

    public function getBio(): ?bool
    {
        return $this->bio;
    }

    public function setBio(?bool $bio): self
    {
        $this->bio = $bio;

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

    public function getDar(): ?string
    {
        return $this->dar;
    }

    public function setDar(?string $dar): self
    {
        $this->dar = $dar;

        return $this;
    }

    public function getZnt(): ?string
    {
        return $this->znt;
    }

    public function setZnt(?string $znt): self
    {
        $this->znt = $znt;

        return $this;
    }

    public function getDre(): ?string
    {
        return $this->dre;
    }

    public function setDre(?string $dre): self
    {
        $this->dre = $dre;

        return $this;
    }

    public function getSecurityMention(): ?string
    {
        return $this->security_mention;
    }

    public function setSecurityMention(?string $security_mention): self
    {
        $this->security_mention = $security_mention;

        return $this;
    }

    public function getDangerMention(): ?string
    {
        return $this->danger_mention;
    }

    public function setDangerMention(?string $danger_mention): self
    {
        $this->danger_mention = $danger_mention;

        return $this;
    }

    public function getWarningMention(): ?string
    {
        return $this->warning_mention;
    }

    public function setWarningMention(?string $warning_mention): self
    {
        $this->warning_mention = $warning_mention;

        return $this;
    }
}
