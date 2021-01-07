<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DosesRepository")
 */
class Doses
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $dose;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $application;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $DAR;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $DRE;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $ZNT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $danger_mention;

    /**
     * @ORM\ManyToOne(targetEntity=IndexCultures::class, inversedBy="doses")
     */
    private $indexCulture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $risk_mention;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $securityMention;

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

    public function getDose(): ?float
    {
        return $this->dose;
    }

    public function setDose(float $dose): self
    {
        $this->dose = $dose;

        return $this;
    }

    public function getApplication(): ?string
    {
        return $this->application;
    }

    public function setApplication(string $application): self
    {
        $this->application = $application;

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

    public function getDAR(): ?string
    {
        return $this->DAR;
    }

    public function setDAR(?string $DAR): self
    {
        $this->DAR = $DAR;

        return $this;
    }

    public function getDRE(): ?string
    {
        return $this->DRE;
    }

    public function setDRE(?string $DRE): self
    {
        $this->DRE = $DRE;

        return $this;
    }

    public function getZNT(): ?string
    {
        return $this->ZNT;
    }

    public function setZNT(?string $ZNT): self
    {
        $this->ZNT = $ZNT;

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

    public function getIndexCulture(): ?IndexCultures
    {
        return $this->indexCulture;
    }

    public function setIndexCulture(?IndexCultures $indexCulture): self
    {
        $this->indexCulture = $indexCulture;

        return $this;
    }

    public function getRiskMention(): ?string
    {
        return $this->risk_mention;
    }

    public function setRiskMention(?string $risk_mention): self
    {
        $this->risk_mention = $risk_mention;

        return $this;
    }

    public function getSecurityMention(): ?string
    {
        return $this->securityMention;
    }

    public function setSecurityMention(?string $securityMention): self
    {
        $this->securityMention = $securityMention;

        return $this;
    }
}
