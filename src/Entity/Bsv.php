<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BsvRepository")
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $third_file;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users", inversedBy="bsvs")
     */
    private $customers;

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
     * @return Collection|Users[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Users $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Users $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
        }

        return $this;
    }
}
