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
     * @ORM\ManyToOne(targetEntity=Phyto::class, inversedBy="interventionsProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $intervention;

    /**
     * @ORM\Column(type="float", nullable=true)
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

    public function setDose($dose): void
    {
        $this->dose = $dose;
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

    /**
     * Function to get IFT
     * @return string
     */
    public function getIft()
    {
        // IF MAKE CHANGE HERE CHANGE ON INTERVENTION
        $surfaceTotal = $this->getIntervention()->getCulture()->getSize();
        $doseApplique = $this->getQuantity() / $surfaceTotal;
        $doseHomologue = $this->getDose();
        $surfaceTraite = $this->getIntervention()->getCulture()->getRealSize();

        //-- Display only if have all value
        if ($doseApplique != null &&
            $doseHomologue != null &&
            $surfaceTraite != null &&
            $surfaceTotal != null) {
            $result = ( $doseApplique / $doseHomologue) * ($surfaceTraite / $surfaceTotal);
            return $result;
        } else {
            return 0;
        }
    }
}
