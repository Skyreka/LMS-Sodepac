<?php

namespace App\Domain\Sales\Entity;

use App\Domain\Sales\Repository\SalesRepository;
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
    private $column1_txt;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $column2_txt;
    
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
    
    // line 1
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $l1_title;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l1_c1_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l1_c2_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l1_variation;
    
    // line 2
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $l2_title;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l2_c1_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l2_c2_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l2_variation;
    
    
    // line 3
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $l3_title;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l3_c1_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l3_c2_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l3_variation;
    
    // line 4
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $l4_title;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l4_c1_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l4_c2_value;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $l4_variation;
    
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
    
    /**
     * @return mixed
     */
    public function getL1C1Value()
    {
        return $this->l1_c1_value;
    }
    
    /**
     * @param mixed $l1_c1_value
     */
    public function setL1C1Value($l1_c1_value): void
    {
        $this->l1_c1_value = $l1_c1_value;
    }
    
    /**
     * @return mixed
     */
    public function getL1C2Value()
    {
        return $this->l1_c2_value;
    }
    
    /**
     * @param mixed $l1_c2_value
     */
    public function setL1C2Value($l1_c2_value): void
    {
        $this->l1_c2_value = $l1_c2_value;
    }
    
    /**
     * @return mixed
     */
    public function getL1Variation()
    {
        return $this->l1_variation;
    }
    
    /**
     * @param mixed $l1_variation
     */
    public function setL1Variation($l1_variation): void
    {
        $this->l1_variation = $l1_variation;
    }
    
    /**
     * @return mixed
     */
    public function getL2C1Value()
    {
        return $this->l2_c1_value;
    }
    
    /**
     * @param mixed $l2_c1_value
     */
    public function setL2C1Value($l2_c1_value): void
    {
        $this->l2_c1_value = $l2_c1_value;
    }
    
    /**
     * @return mixed
     */
    public function getL2C2Value()
    {
        return $this->l2_c2_value;
    }
    
    /**
     * @param mixed $l2_c2_value
     */
    public function setL2C2Value($l2_c2_value): void
    {
        $this->l2_c2_value = $l2_c2_value;
    }
    
    /**
     * @return mixed
     */
    public function getL2Variation()
    {
        return $this->l2_variation;
    }
    
    /**
     * @param mixed $l2_variation
     */
    public function setL2Variation($l2_variation): void
    {
        $this->l2_variation = $l2_variation;
    }
    
    /**
     * @return mixed
     */
    public function getL3C1Value()
    {
        return $this->l3_c1_value;
    }
    
    /**
     * @param mixed $l3_c1_value
     */
    public function setL3C1Value($l3_c1_value): void
    {
        $this->l3_c1_value = $l3_c1_value;
    }
    
    /**
     * @return mixed
     */
    public function getL3C2Value()
    {
        return $this->l3_c2_value;
    }
    
    /**
     * @param mixed $l3_c2_value
     */
    public function setL3C2Value($l3_c2_value): void
    {
        $this->l3_c2_value = $l3_c2_value;
    }
    
    /**
     * @return mixed
     */
    public function getL3Variation()
    {
        return $this->l3_variation;
    }
    
    /**
     * @param mixed $l3_variation
     */
    public function setL3Variation($l3_variation): void
    {
        $this->l3_variation = $l3_variation;
    }
    
    /**
     * @return mixed
     */
    public function getL4C1Value()
    {
        return $this->l4_c1_value;
    }
    
    /**
     * @param mixed $l4_c1_value
     */
    public function setL4C1Value($l4_c1_value): void
    {
        $this->l4_c1_value = $l4_c1_value;
    }
    
    /**
     * @return mixed
     */
    public function getL4C2Value()
    {
        return $this->l4_c2_value;
    }
    
    /**
     * @param mixed $l4_c2_value
     */
    public function setL4C2Value($l4_c2_value): void
    {
        $this->l4_c2_value = $l4_c2_value;
    }
    
    /**
     * @return mixed
     */
    public function getL4Variation()
    {
        return $this->l4_variation;
    }
    
    /**
     * @param mixed $l4_variation
     */
    public function setL4Variation($l4_variation): void
    {
        $this->l4_variation = $l4_variation;
    }
    
    /**
     * @return mixed
     */
    public function getColumn1Txt()
    {
        return $this->column1_txt;
    }
    
    /**
     * @param mixed $column1_txt
     */
    public function setColumn1Txt($column1_txt): void
    {
        $this->column1_txt = $column1_txt;
    }
    
    /**
     * @return mixed
     */
    public function getColumn2Txt()
    {
        return $this->column2_txt;
    }
    
    /**
     * @param mixed $column2_txt
     */
    public function setColumn2Txt($column2_txt): void
    {
        $this->column2_txt = $column2_txt;
    }
    
    /**
     * @return mixed
     */
    public function getL4Title()
    {
        return $this->l4_title;
    }
    
    /**
     * @param mixed $l4_title
     */
    public function setL4Title($l4_title): void
    {
        $this->l4_title = $l4_title;
    }
    
    /**
     * @return mixed
     */
    public function getL3Title()
    {
        return $this->l3_title;
    }
    
    /**
     * @param mixed $l3_title
     */
    public function setL3Title($l3_title): void
    {
        $this->l3_title = $l3_title;
    }
    
    /**
     * @return mixed
     */
    public function getL1Title()
    {
        return $this->l1_title;
    }
    
    /**
     * @param mixed $l1_title
     */
    public function setL1Title($l1_title): void
    {
        $this->l1_title = $l1_title;
    }
    
    /**
     * @return mixed
     */
    public function getL2Title()
    {
        return $this->l2_title;
    }
    
    /**
     * @param mixed $l2_title
     */
    public function setL2Title($l2_title): void
    {
        $this->l2_title = $l2_title;
    }
}
