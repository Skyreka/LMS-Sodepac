<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    const STATUS = [
        0 => 'Draft',
        1 => 'Create',
        2 => 'Wait Signature',
        3 => 'Sign'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $id_number;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=OrdersProduct::class, mappedBy="orders", orphanRemoval=true, fetch="EAGER")
     */
    private $ordersProducts;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $delivery;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $conditions;

    /**
     * @ORM\OneToOne(targetEntity=Signature::class, inversedBy="order", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private $signature;

    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->ordersProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdNumber(): ?string
    {
        return $this->id_number;
    }

    public function setIdNumber(string $id_number): self
    {
        $this->id_number = $id_number;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
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

    /**
     * @return Collection|OrdersProduct[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->ordersProducts;
    }

    public function addOrderProduct(OrdersProduct $ordersProduct): self
    {
        if (!$this->ordersProducts->contains($ordersProduct)) {
            $this->ordersProducts[] = $ordersProduct;
            $ordersProduct->setOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrdersProduct $ordersProduct): self
    {
        if ($this->ordersProducts->removeElement($ordersProduct)) {
            // set the owning side to null (unless already changed)
            if ($ordersProduct->getOrders() === $this) {
                $ordersProduct->setOrders(null);
            }
        }

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

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(?string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
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
}
