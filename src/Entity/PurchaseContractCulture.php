<?php

namespace App\Entity;

use App\Repository\PurchaseContractCultureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseContractCultureRepository::class)
 */
class PurchaseContractCulture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PurchaseContract::class, inversedBy="cultures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $purchaseContract;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $culture;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $volume;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transport;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $depot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recovery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $divers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseContract(): ?PurchaseContract
    {
        return $this->purchaseContract;
    }

    public function setPurchaseContract(?PurchaseContract $purchaseContract): self
    {
        $this->purchaseContract = $purchaseContract;

        return $this;
    }

    public function getCulture(): ?string
    {
        return $this->culture;
    }

    public function setCulture(?string $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): self
    {
        $this->volume = $volume;

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

    public function getTransport(): ?int
    {
        return $this->transport;
    }

    public function setTransport(?int $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    public function getDepot(): ?int
    {
        return $this->depot;
    }

    public function setDepot(?int $depot): self
    {
        $this->depot = $depot;

        return $this;
    }

    public function getRecovery(): ?int
    {
        return $this->recovery;
    }

    public function setRecovery(?int $recovery): self
    {
        $this->recovery = $recovery;

        return $this;
    }

    public function getDivers(): ?string
    {
        return $this->divers;
    }

    public function setDivers(?string $divers): self
    {
        $this->divers = $divers;

        return $this;
    }
}
