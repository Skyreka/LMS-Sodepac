<?php

namespace App\Domain\Catalogue\Entity;

use App\Domain\Auth\Users;
use App\Domain\Catalogue\Repository\CatalogueRepository;
use App\Domain\Catalogue\Entity\CanevasIndex;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatalogueRepository::class)
 */
class Catalogue
{
    final public const DRAFT = 0;
    final public const CREATE = 1;
    final public const GENERATE = 2;
    final public const VALIDATE = 3;

    const STATUS = [
        0 => ['Brouillon', ''],
        1 => ['Crée', 'warning'],
        2 => ['Généré', 'info'],
        3 => ['Validé', 'success']
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="catalogues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=CanevasIndex::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $canevas;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mention;

    /**
     * @ORM\Column(type="float")
     */
    private $cultureSize;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $addedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=CatalogueProducts::class, mappedBy="catalogue", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCanevas(): ?CanevasIndex
    {
        return $this->canevas;
    }

    public function setCanevas(?CanevasIndex $canevas): self
    {
        $this->canevas = $canevas;

        return $this;
    }

    public function getStatus($params = []): string
    {
        if(isset($params['label'])) {
            return self::STATUS[$this->status][1];
        }
        if(isset($params['word'])) {
            return self::STATUS[$this->status][0];
        }
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateBy(): ?Users
    {
        return $this->createBy;
    }

    public function setCreateBy(?Users $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->mention;
    }

    public function setMention(?string $mention): self
    {
        $this->mention = $mention;

        return $this;
    }

    public function getCultureSize(): ?float
    {
        return $this->cultureSize;
    }

    public function setCultureSize(float $cultureSize): self
    {
        $this->cultureSize = $cultureSize;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, CatalogueProducts>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(CatalogueProducts $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCatalogue($this);
        }

        return $this;
    }

    public function removeProduct(CatalogueProducts $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCatalogue() === $this) {
                $product->setCatalogue(null);
            }
        }

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }
}
