<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StocksRepository")
 */
class Stocks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exploitation", inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products", inversedBy="stocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $unit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExploitation(): ?Exploitation
    {
        return $this->exploitation;
    }

    public function setExploitation(?Exploitation $exploitation): self
    {
        $this->exploitation = $exploitation;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

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
}
