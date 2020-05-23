<?php

namespace App\Entity;

use App\Repository\InterventionsProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterventionsProductsRepository::class)
 */
class InterventionsProducts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Interventions::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $intervention;

    /**
     * @ORM\Column(type="float")
     */
    private $dose;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIntervention(): ?Interventions
    {
        return $this->intervention;
    }

    public function setIntervention(?Interventions $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }

    public function getDose(): ?float
    {
        return $this->dose;
    }

    public function setDose(float $dose): self
    {
        $this->dose = $dose;

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
}
