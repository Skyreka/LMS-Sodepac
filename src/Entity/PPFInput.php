<?php

namespace App\Entity;

use App\Repository\PPFInputRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PPFInputRepository::class)
 * @ORM\Table(name="ppf_input")
 */
class PPFInput
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PPF::class, inversedBy="inputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ppf;

    /**
     * @ORM\Column(type="date")
     */
    private $date_added;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $n;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $p;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $k;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPpf(): ?PPF
    {
        return $this->ppf;
    }

    public function setPpf(?PPF $ppf): self
    {
        $this->ppf = $ppf;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTimeInterface $date_added): self
    {
        $this->date_added = $date_added;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getN(): ?float
    {
        return $this->n;
    }

    public function setN(?float $n): self
    {
        $this->n = $n;

        return $this;
    }

    public function getP(): ?float
    {
        return $this->p;
    }

    public function setP(?float $p): self
    {
        $this->p = $p;

        return $this;
    }

    public function getK(): ?float
    {
        return $this->k;
    }

    public function setK(?float $k): self
    {
        $this->k = $k;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
