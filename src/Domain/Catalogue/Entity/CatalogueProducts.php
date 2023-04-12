<?php

namespace App\Domain\Catalogue\Entity;

use App\Domain\Catalogue\Repository\CatalogueProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatalogueProductsRepository::class)
 */
class CatalogueProducts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catalogue::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogue;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasProduct::class, inversedBy="catalogueProducts")
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityUnit;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $doseEdit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalogue(): ?Catalogue
    {
        return $this->catalogue;
    }

    public function setCatalogue(?Catalogue $catalogue): self
    {
        $this->catalogue = $catalogue;

        return $this;
    }

    public function getProduct(): ?CanevasProduct
    {
        return $this->product;
    }

    public function setProduct(?CanevasProduct $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityUnit(): ?int
    {
        return $this->quantityUnit;
    }

    public function setQuantityUnit(?int $quantityUnit): self
    {
        $this->quantityUnit = $quantityUnit;

        return $this;
    }

    public function getDoseEdit(): ?float
    {
        if( $this->doseEdit === NULL) {
            return $this->product->getDose();
        }
        return $this->doseEdit;
    }

    public function setDoseEdit(?float $doseEdit): self
    {
        $this->doseEdit = $doseEdit;

        return $this;
    }
}
