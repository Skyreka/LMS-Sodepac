<?php

namespace App\Domain\Mix\Entity;

use App\Domain\Auth\Users;
use App\Domain\Mix\Repository\MixRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MixRepository::class)
 */
class Mix
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="mixes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @ORM\OneToMany(targetEntity=MixProducts::class, mappedBy="mix", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $mixProducts;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getUser(): ?Users
    {
        return $this->user;
    }
    
    public function setUser(?Users $user): self
    {
        $this->user = $user;
        
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
    
    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }
    
    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;
        
        return $this;
    }
    
    
    public function removeMixProducts(MixProducts $mixProducts): self
    {
        if($this->mixProducts->contains($mixProducts)) {
            $this->mixProducts->removeElement($mixProducts);
            // set the owning side to null (unless already changed)
            if($mixProducts->getMix() === $this) {
                $mixProducts->setMix(null);
            }
        }
        
        return $this;
    }
}
