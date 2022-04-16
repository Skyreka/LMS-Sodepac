<?php

namespace App\Domain\Signature\Entity;

use App\Domain\Signature\Repository\SignatureOtpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SignatureOtpRepository::class)
 */
class SignatureOtp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Signature::class, inversedBy="opts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $signature;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = 1;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $expiredAt;
    
    public function __construct()
    {
        $expireAt = new \DateTime();
        $expireAt = $expireAt->modify('+1 month');
        
        $otp = random_int(100000, 999999);
        $this->setAddedAt(new \DateTime());
        $this->setExpiredAt($expireAt);
        $this->setCode($otp);
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getSignature(): ?Signature
    {
        return $this->signature;
    }
    
    public function setSignature(?Signature $signature): self
    {
        $this->signature = $signature;
        
        return $this;
    }
    
    public function getCode(): ?string
    {
        return $this->code;
    }
    
    public function setCode(string $code): self
    {
        $this->code = $code;
        
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
    
    public function getUpdateat(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }
    
    public function setUpdateat(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;
        
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
    
    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }
    
    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        
        return $this;
    }
}
