<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanoramaUserRepository")
 */
class PanoramaUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="panoramas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Panoramas", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $panorama;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $display_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="panoramas_sent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomers(): ?Users
    {
        return $this->customers;
    }

    public function setCustomers(?Users $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    public function getPanorama(): ?Panoramas
    {
        return $this->panorama;
    }

    public function setPanorama(?Panoramas $panorama): self
    {
        $this->panorama = $panorama;

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

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function getSender(): ?Users
    {
        return $this->sender;
    }

    public function setSender(?Users $sender): self
    {
        $this->sender = $sender;

        return $this;
    }
}
