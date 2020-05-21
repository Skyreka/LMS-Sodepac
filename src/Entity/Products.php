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
}
