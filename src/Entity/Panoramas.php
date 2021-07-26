<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanoramasRepository")
 */
class Panoramas
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(maxSize="5M")
     */
    private $first_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(maxSize="5M")
     */
    private $second_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(maxSize="5M")
     */
    private $third_file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $send_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PanoramaUser", mappedBy="panorama", orphanRemoval=true)
     */
    private $customers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="ownedPanoramas")
     */
    private $owner;

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

    public function getThirdFile(): ?string
    {
        return $this->third_file;
    }

    public function setThirdFile(?string $third_file): self
    {
        $this->third_file = $third_file;

        return $this;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

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

    public function setSendDate(?\DateTimeInterface $send_date): self
    {
        $this->send_date = $send_date;

        return $this;
    }

    /**
     * @return Collection|PanoramaUser[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(PanoramaUser $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setPanorama($this);
        }

        return $this;
    }

    public function removeCustomer(PanoramaUser $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getPanorama() === $this) {
                $customer->setPanorama(null);
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

    public function getOwner(): ?Users
    {
        return $this->owner;
    }

    public function setOwner(?Users $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
