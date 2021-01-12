<?php

namespace App\Entity;

use App\Repository\PurchaseContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    const CULTURES = [
        'Blé Tendre',
        'Bologna/Izalco',
        'Orge Mouture',
        'Triticale',
        'Colza',
        'Tournesol Lino',
        'Tournesol Oléique',
        'Maïs',
        'Sorgho',
        'Soja',
    ];

    public function __construct()
    {
        $this->added_date = new \DateTime();
        $this->cultures = new ArrayCollection();
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

    /**
     * @ORM\OneToMany(targetEntity=PurchaseContractCulture::class, mappedBy="purchaseContract", orphanRemoval=true)
     */
    private $cultures;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

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

    public function getCultureType( $return = false ): ?string
    {
        if ( $return ) {
            return self::CULTURETYPE[ $this->cultureType ];
        }
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

    /**
     * @return Collection|PurchaseContractCulture[]
     */
    public function getCultures(): Collection
    {
        return $this->cultures;
    }

    public function addCulture(PurchaseContractCulture $culture): self
    {
        if (!$this->cultures->contains($culture)) {
            $this->cultures[] = $culture;
            $culture->setPurchaseContract($this);
        }

        return $this;
    }

    public function removeCulture(PurchaseContractCulture $culture): self
    {
        if ($this->cultures->removeElement($culture)) {
            // set the owning side to null (unless already changed)
            if ($culture->getPurchaseContract() === $this) {
                $culture->setPurchaseContract(null);
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
}
