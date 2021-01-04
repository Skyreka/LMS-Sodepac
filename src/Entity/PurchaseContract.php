<?php

namespace App\Entity;

use App\Repository\PurchaseContractRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseContractRepository::class)
 */
class PurchaseContract
{
    const CULTURETYPE = [
        1 => 'Conventionnel',
        2 => 'Bio',
        3 => 'C2'
    ];

    public function __construct()
    {
        $this->added_date = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="purchaseContracts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     */
    private $cultureType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $added_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?Users
    {
        return $this->creator;
    }

    public function setCreator(?Users $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCustomer(): ?Users
    {
        return $this->customer;
    }

    public function setCustomer(?Users $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCultureType(): ?int
    {
        return $this->cultureType;
    }

    public function setCultureType(int $cultureType): self
    {
        $this->cultureType = $cultureType;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(\DateTimeInterface $added_date): self
    {
        $this->added_date = $added_date;

        return $this;
    }
}
