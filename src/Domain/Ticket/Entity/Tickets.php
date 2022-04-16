<?php

namespace App\Domain\Ticket\Entity;

use App\Domain\Auth\Users;
use App\Domain\Ticket\Repository\TicketsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketsRepository::class)
 */
class Tickets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $technician;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $status = 1;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closed_at;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTechnician(): ?Users
    {
        return $this->technician;
    }
    
    public function setTechnician(?Users $technician): self
    {
        $this->technician = $technician;
        
        return $this;
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
    
    public function getStatus(): ?int
    {
        return $this->status;
    }
    
    public function setStatus(int $status): self
    {
        $this->status = $status;
        
        return $this;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getClosedAt(): ?\DateTimeInterface
    {
        return $this->closed_at;
    }
    
    public function setClosedAt(?\DateTimeInterface $closed_at): self
    {
        $this->closed_at = $closed_at;
        
        return $this;
    }
}
