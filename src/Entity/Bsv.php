<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_file;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $second_file;

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
     * @ORM\Column(type="integer")
     */
    private $technician;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $customers = [];

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

    public function getTechnician(): ?int
    {
        return $this->technician;
    }

    public function setTechnician(int $technician): self
    {
        $this->technician = $technician;

        return $this;
    }

    public function getCustomers(): ?array
    {
        return $this->customers;
    }

    public function setCustomers(?array $customers): self
    {
        $this->customers = $customers;

        return $this;
    }
}
