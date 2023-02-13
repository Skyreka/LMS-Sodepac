<?php

namespace App\Domain\Catalogue\Entity;

use App\Domain\Catalogue\Repository\CanevasDiseaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CanevasDiseaseRepository::class)
 */
class CanevasDisease
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasIndex::class, inversedBy="diseases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $canevas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sort;

    /**
     * @ORM\OneToMany(targetEntity=CanevasProduct::class, mappedBy="disease", orphanRemoval=true)
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanevas(): ?CanevasIndex
    {
        return $this->canevas;
    }

    public function setCanevas(?CanevasIndex $canevas): self
    {
        $this->canevas = $canevas;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

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
            $product->setDisease($this);
        }

        return $this;
    }

    public function removeProduct(CanevasProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getDisease() === $this) {
                $product->setDisease(null);
            }
        }

        return $this;
    }
}
