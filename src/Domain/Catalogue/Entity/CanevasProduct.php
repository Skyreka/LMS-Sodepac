<?php

namespace App\Domain\Catalogue\Entity;

use App\Domain\Catalogue\Repository\CanevasProductRepository;
use App\Domain\Product\Entity\Products;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CanevasProductRepository::class)
 */
class CanevasProduct
{
    const COLORS = [
        'R' => 'R - Rose',
        'O' => 'O - Orange',
        'J' => 'J - Jaune claire',
        'V' => 'V - Vert claire',
        'C' => 'C - Bleu claire',
        'B' => 'B - Violet',
        'M' => 'M - Rose',
        'A' => 'A - Gris'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasIndex::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $canevas;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasStep::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $step;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasDisease::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $disease;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="canevasProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dose;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=CatalogueProducts::class, mappedBy="product")
     */
    private $catalogueProducts;

    public function __construct()
    {
        $this->catalogueProducts = new ArrayCollection();
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

    public function getStep(): ?CanevasStep
    {
        return $this->step;
    }

    public function setStep(?CanevasStep $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getDisease(): ?CanevasDisease
    {
        return $this->disease;
    }

    public function setDisease(?CanevasDisease $disease): self
    {
        $this->disease = $disease;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDose(): ?float
    {
        return $this->dose;
    }

    public function setDose(?float $dose): self
    {
        $this->dose = $dose;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, CatalogueProducts>
     */
    public function getCatalogueProducts(): Collection
    {
        return $this->catalogueProducts;
    }

    public function addCatalogueProduct(CatalogueProducts $catalogueProduct): self
    {
        if (!$this->catalogueProducts->contains($catalogueProduct)) {
            $this->catalogueProducts[] = $catalogueProduct;
            $catalogueProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCatalogueProduct(CatalogueProducts $catalogueProduct): self
    {
        if ($this->catalogueProducts->removeElement($catalogueProduct)) {
            // set the owning side to null (unless already changed)
            if ($catalogueProduct->getProduct() === $this) {
                $catalogueProduct->setProduct(null);
            }
        }

        return $this;
    }
}
