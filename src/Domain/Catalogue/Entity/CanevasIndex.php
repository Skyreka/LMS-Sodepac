<?php

namespace App\Domain\Catalogue\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Catalogue\Repository\CanevasIndexRepository;

/**
 * @ORM\Entity(repositoryClass=CanevasIndexRepository::class)
 */
class CanevasIndex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = 1;

    /**
     * @ORM\OneToMany(targetEntity=CanevasStep::class, mappedBy="canevas", orphanRemoval=true)
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity=CanevasDisease::class, mappedBy="canevas", orphanRemoval=true)
     */
    private $diseases;

    /**
     * @ORM\OneToMany(targetEntity=CanevasProduct::class, mappedBy="canevas", orphanRemoval=true)
     */
    private $products;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->diseases = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, CanevasStep>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(CanevasStep $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setCanevas($this);
        }

        return $this;
    }

    public function removeStep(CanevasStep $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getCanevas() === $this) {
                $step->setCanevas(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CanevasDisease>
     */
    public function getDiseases(): Collection
    {
        return $this->diseases;
    }

    public function addDisease(CanevasDisease $disease): self
    {
        if (!$this->diseases->contains($disease)) {
            $this->diseases[] = $disease;
            $disease->setCanevas($this);
        }

        return $this;
    }

    public function removeDisease(CanevasDisease $disease): self
    {
        if ($this->diseases->removeElement($disease)) {
            // set the owning side to null (unless already changed)
            if ($disease->getCanevas() === $this) {
                $disease->setCanevas(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CanevasProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(CanevasProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCanevas($this);
        }

        return $this;
    }

    public function removeProduct(CanevasProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCanevas() === $this) {
                $product->setCanevas(null);
            }
        }

        return $this;
    }
}
