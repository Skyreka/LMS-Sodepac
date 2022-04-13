<?php

namespace App\Domain\Bsv\Entity;

use App\Domain\Bsv\Repository\BsvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BsvRepository::class)
 */
class Bsv
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $first_file;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $second_file;
    
    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $sent = 0;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $send_date;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $third_file;
    
    /**
     * @ORM\OneToMany(targetEntity=BsvUsers::class, mappedBy="bsv")
     */
    private $customers;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;
    
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getText(): ?string
    {
        return $this->text;
    }
    
    public function setText(?string $text): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    public function getFirstFile(): ?string
    {
        return $this->first_file;
    }
    
    public function setFirstFile(?string $first_file): self
    {
        $this->first_file = $first_file;
        
        return $this;
    }
    
    public function getSecondFile(): ?string
    {
        return $this->second_file;
    }
    
    public function setSecondFile(?string $second_file): self
    {
        $this->second_file = $second_file;
        
        return $this;
    }
    
    public function getSent(): ?bool
    {
        return $this->sent;
    }
    
    public function setSent(bool $sent): self
    {
        $this->sent = $sent;
        
        return $this;
    }
    
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }
    
    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;
        
        return $this;
    }
    
    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->send_date;
    }
    
    public function setSendDate(\DateTimeInterface $send_date): self
    {
        $this->send_date = $send_date;
        
        return $this;
    }
    
    public function getThirdFile(): ?string
    {
        return $this->third_file;
    }
    
    public function setThirdFile(?string $third_file): self
    {
        $this->third_file = $third_file;
        
        return $this;
    }
    
    /**
     * @return Collection|BsvUsers[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }
    
    public function addCustomer(BsvUsers $customer): self
    {
        if(! $this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setBsv($this);
        }
        
        return $this;
    }
    
    public function removeCustomer(BsvUsers $customer): self
    {
        if($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if($customer->getBsv() === $this) {
                $customer->setBsv(null);
            }
        }
        
        return $this;
    }
    
    public function getArchive(): ?bool
    {
        return $this->archive;
    }
    
    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;
        
        return $this;
    }
    
}
