<?php

namespace App\Domain\Signature\Entity;

use App\Domain\Order\Entity\Orders;
use App\Domain\Signature\Repository\SignatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SignatureRepository::class)
 */
class Signature
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
    private $token;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $identity;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signAt;
    
    /**
     * @ORM\OneToMany(targetEntity=SignatureOtp::class, mappedBy="signature", orphanRemoval=true)
     */
    private $opts;
    
    /**
     * @ORM\OneToOne(targetEntity=Orders::class, mappedBy="signature", cascade={"persist", "remove"})
     */
    private $order;
    
    /**
     * @ORM\OneToOne(targetEntity=SignatureOtp::class, cascade={"persist", "remove"})
     */
    private $codeOtp;
    
    public function __construct()
    {
        $token = bin2hex(random_bytes(32));
        $this->setToken($token);
        $this->setAddedAt(new \DateTime());
        $this->opts = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getToken(): ?string
    {
        return $this->token;
    }
    
    public function setToken(string $token): self
    {
        $this->token = $token;
        
        return $this;
    }
    
    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }
    
    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;
        
        return $this;
    }
    
    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }
    
    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;
        
        return $this;
    }
    
    public function getIdentity(): ?string
    {
        return $this->identity;
    }
    
    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;
        
        return $this;
    }
    
    public function getSignAt(): ?\DateTimeInterface
    {
        return $this->signAt;
    }
    
    public function setSignAt(?\DateTimeInterface $signAt): self
    {
        $this->signAt = $signAt;
        
        return $this;
    }
    
    /**
     * @return Collection|SignatureOtp[]
     */
    public function getOpts(): Collection
    {
        return $this->opts;
    }
    
    public function addOpt(SignatureOtp $opt): self
    {
        if(! $this->opts->contains($opt)) {
            $this->opts[] = $opt;
            $opt->setSignature($this);
        }
        
        return $this;
    }
    
    public function removeOpt(SignatureOtp $opt): self
    {
        if($this->opts->removeElement($opt)) {
            // set the owning side to null (unless already changed)
            if($opt->getSignature() === $this) {
                $opt->setSignature(null);
            }
        }
        
        return $this;
    }
    
    public function getOrder(): ?Orders
    {
        return $this->order;
    }
    
    public function setOrder(?Orders $order): self
    {
        // unset the owning side of the relation if necessary
        if($order === null && $this->order !== null) {
            $this->order->setSignature(null);
        }
        
        // set the owning side of the relation if necessary
        if($order !== null && $order->getSignature() !== $this) {
            $order->setSignature($this);
        }
        
        $this->order = $order;
        
        return $this;
    }
    
    public function getCodeOtp(): ?SignatureOtp
    {
        return $this->codeOtp;
    }
    
    public function setCodeOtp(?SignatureOtp $codeOtp): self
    {
        $this->codeOtp = $codeOtp;
        
        return $this;
    }
}
