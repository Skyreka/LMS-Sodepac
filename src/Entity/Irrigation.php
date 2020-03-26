<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IrrigationRepository")
 */
class Irrigation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ilots", inversedBy="irrigations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ilot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exploitation", inversedBy="irrigations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $intervention_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIlot(): ?Ilots
    {
        return $this->ilot;
    }

    public function setIlot(?Ilots $ilot): self
    {
        $this->ilot = $ilot;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getInterventionAt(): ?\DateTimeInterface
    {
        return $this->intervention_at;
    }

    public function setInterventionAt(?\DateTimeInterface $intervention_at): self
    {
        $this->intervention_at = $intervention_at;

        return $this;
    }
}
