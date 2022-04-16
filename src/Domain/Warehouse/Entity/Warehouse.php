<?php

namespace App\Domain\Warehouse\Entity;

use App\Domain\Auth\Users;
use App\Domain\Warehouse\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarehouseRepository::class)
 */
class Warehouse
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
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    
    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="warehouse")
     */
    private $users;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getAddress(): ?string
    {
        return $this->address;
    }
    
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        
        return $this;
    }
    
    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
    
    public function addUser(Users $user): self
    {
        if(! $this->users->contains($user)) {
            $this->users[] = $user;
            $user->setWarehouse($this);
        }
        
        return $this;
    }
    
    public function removeUser(Users $user): self
    {
        if($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if($user->getWarehouse() === $this) {
                $user->setWarehouse(null);
            }
        }
        
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        
        return $this;
    }
}
