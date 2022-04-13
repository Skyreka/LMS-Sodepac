<?php

namespace App\Domain\Product\Entity;

use App\Domain\Product\Repository\ProductsRepository;
use App\Domain\Stock\Entity\Stocks;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
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
     * @ORM\OneToMany(targetEntity=Stocks::class, mappedBy="product", orphanRemoval=true)
     */
    private $stocks;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;
    
    /**
     * @ORM\OneToMany(targetEntity=RiskPhase::class, mappedBy="product")
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
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RPD;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $n;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $p;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $k;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity=ProductCategory::class, inversedBy="products")
     */
    private $category;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = 1;
    
    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="childs")
     */
    private $parent_product;
    
    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="parent_product")
     */
    private $childs;
    
    public function __construct()
    {
        $this->stocks     = new ArrayCollection();
        $this->riskPhases = new ArrayCollection();
        $this->childs     = new ArrayCollection();
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
        if(! $this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduct($this);
        }
        
        return $this;
    }
    
    public function removeStock(Stocks $stock): self
    {
        if($this->stocks->contains($stock)) {
            $this->stocks->removeElement($stock);
            // set the owning side to null (unless already changed)
            if($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }
        
        return $this;
    }
    
    public function __toString(): string
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
    
    /**
     * @return Collection|RiskPhase[]
     */
    public function getRiskPhases(): Collection
    {
        return $this->riskPhases;
    }
    
    public function addRiskPhase(RiskPhase $riskPhase): self
    {
        if(! $this->riskPhases->contains($riskPhase)) {
            $this->riskPhases[] = $riskPhase;
            $riskPhase->setProduct($this);
        }
        
        return $this;
    }
    
    public function removeRiskPhase(RiskPhase $riskPhase): self
    {
        if($this->riskPhases->contains($riskPhase)) {
            $this->riskPhases->removeElement($riskPhase);
            // set the owning side to null (unless already changed)
            if($riskPhase->getProduct() === $this) {
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
    
    public function getRPD(): ?float
    {
        return $this->RPD;
    }
    
    public function setRPD(?float $RPD): self
    {
        $this->RPD = $RPD;
        
        return $this;
    }
    
    public function getN(): ?float
    {
        return $this->n;
    }
    
    public function setN(?float $n): self
    {
        $this->n = $n;
        
        return $this;
    }
    
    public function getP(): ?float
    {
        return $this->p;
    }
    
    public function setP(?float $p): self
    {
        $this->p = $p;
        
        return $this;
    }
    
    public function getK(): ?float
    {
        return $this->k;
    }
    
    public function setK(?float $k): self
    {
        $this->k = $k;
        
        return $this;
    }
    
    public function getPrice(): ?float
    {
        return $this->price;
    }
    
    public function setPrice(?float $price): self
    {
        $this->price = $price;
        
        return $this;
    }
    
    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }
    
    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;
        
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
    
    public function getParentProduct(): ?self
    {
        return $this->parent_product;
    }
    
    public function setParentProduct(?self $parent_product): self
    {
        $this->parent_product = $parent_product;
        
        return $this;
    }
    
    /**
     * @return Collection|self[]
     */
    public function getChilds(): Collection
    {
        return $this->childs;
    }
    
    public function addChild(self $child): self
    {
        if(! $this->childs->contains($child)) {
            $this->childs[] = $child;
            $child->setParentProduct($this);
        }
        
        return $this;
    }
    
    public function removeChild(self $child): self
    {
        if($this->childs->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if($child->getParentProduct() === $this) {
                $child->setParentProduct(null);
            }
        }
        
        return $this;
    }
}
