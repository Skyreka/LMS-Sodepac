<?php

namespace App\Domain\Bsv\Entity;

use App\Domain\Auth\Users;
use App\Domain\Bsv\Repository\BsvUsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BsvUsersRepository::class)
 */
class BsvUsers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Bsv::class, inversedBy="customers")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $bsv;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="bsvs")
     */
    private $customers;
    
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $checked;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $display_at;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getBsv(): ?bsv
    {
        return $this->bsv;
    }
    
    public function setBsv(?bsv $bsv): self
    {
        $this->bsv = $bsv;
        
        return $this;
    }
    
    public function getCustomers(): ?users
    {
        return $this->customers;
    }
    
    public function setCustomers(?users $customers): self
    {
        $this->customers = $customers;
        
        return $this;
    }
    
    public function getChecked(): ?bool
    {
        return $this->checked;
    }
    
    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;
        
        return $this;
    }
    
    public function getDisplayAt(): ?\DateTimeInterface
    {
        return $this->display_at;
    }
    
    public function setDisplayAt(\DateTimeInterface $display_at): self
    {
        $this->display_at = $display_at;
        
        return $this;
    }
}
