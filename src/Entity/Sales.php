<?php

namespace App\Entity;

use App\Repository\SalesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalesRepository::class)
 */
class Sales
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $culture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brs1_txt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brs2_txt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $added_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = 1;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs1_deposit_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs1_crop_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs2_deposit_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs2_crop_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs_deposit_variation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $brs_crop_variation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCulture(): ?string
    {
        return $this->culture;
    }

    public function setCulture(string $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBrs1Txt(): ?string
    {
        return $this->brs1_txt;
    }

    public function setBrs1Txt(?string $brs1_txt): self
    {
        $this->brs1_txt = $brs1_txt;

        return $this;
    }

    public function getBrs2Txt(): ?string
    {
        return $this->brs2_txt;
    }

    public function setBrs2Txt(?string $brs2_txt): self
    {
        $this->brs2_txt = $brs2_txt;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(?\DateTimeInterface $added_date): self
    {
        $this->added_date = $added_date;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getBrs1DepositValue(): ?float
    {
        return $this->brs1_deposit_value;
    }

    public function setBrs1DepositValue(?float $brs1_deposit_value): self
    {
        $this->brs1_deposit_value = $brs1_deposit_value;

        return $this;
    }

    public function getBrs1CropValue(): ?float
    {
        return $this->brs1_crop_value;
    }

    public function setBrs1CropValue(?float $brs1_crop_value): self
    {
        $this->brs1_crop_value = $brs1_crop_value;

        return $this;
    }

    public function getBrs2DepositValue(): ?float
    {
        return $this->brs2_deposit_value;
    }

    public function setBrs2DepositValue(?float $brs2_deposit_value): self
    {
        $this->brs2_deposit_value = $brs2_deposit_value;

        return $this;
    }

    public function getBrs2CropValue(): ?float
    {
        return $this->brs2_crop_value;
    }

    public function setBrs2CropValue(?float $brs2_crop_value): self
    {
        $this->brs2_crop_value = $brs2_crop_value;

        return $this;
    }

    public function getBrsDepositVariation(): ?float
    {
        return $this->brs_deposit_variation;
    }

    public function setBrsDepositVariation(?float $brs_deposit_variation): self
    {
        $this->brs_deposit_variation = $brs_deposit_variation;

        return $this;
    }

    public function getBrsCropVariation(): ?float
    {
        return $this->brs_crop_variation;
    }

    public function setBrsCropVariation(?float $brs_crop_variation): self
    {
        $this->brs_crop_variation = $brs_crop_variation;

        return $this;
    }
}
