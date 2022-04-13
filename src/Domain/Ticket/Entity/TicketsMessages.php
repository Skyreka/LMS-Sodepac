<?php

namespace App\Domain\Ticket\Entity;

use App\Domain\Auth\Users;
use App\Domain\Ticket\Repository\TicketsMessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketsMessagesRepository::class)
 */
class TicketsMessages
{
    
    public function __construct()
    {
        $this->send_at = new \DateTime();
    }
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $from;
    
    /**
     * @ORM\ManyToOne(targetEntity=Tickets::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $send_at;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFromId(): ?Users
    {
        return $this->from;
    }
    
    public function setFromId(?Users $from): self
    {
        $this->from = $from;
        
        return $this;
    }
    
    public function getTicket(): ?Tickets
    {
        return $this->ticket;
    }
    
    public function setTicket(?Tickets $ticket): self
    {
        $this->ticket = $ticket;
        
        return $this;
    }
    
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    public function setContent(?string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    public function getSendAt(): ?\DateTimeInterface
    {
        return $this->send_at;
    }
    
    public function setSendAt(\DateTimeInterface $send_at): self
    {
        $this->send_at = $send_at;
        
        return $this;
    }
    
    public function getFile(): ?string
    {
        return $this->file;
    }
    
    public function setFile(?string $file): self
    {
        $this->file = $file;
        
        return $this;
    }
}
