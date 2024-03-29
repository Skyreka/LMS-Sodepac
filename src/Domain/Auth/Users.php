<?php

namespace App\Domain\Auth;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Bsv\Entity\BsvUsers;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Mix\Entity\Mix;
use App\Domain\Order\Entity\Orders;
use App\Domain\Panorama\Entity\Panorama;
use App\Domain\Panorama\Entity\PanoramaSend;
use App\Domain\Purchase\Entity\PurchaseContract;
use App\Domain\Ticket\Entity\Tickets;
use App\Domain\Warehouse\Entity\Warehouse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity("email", message="Il existe déjà un compte avec cet e-mail")
 */
class Users implements UserInterface
{
    const STATUS = [
        'ROLE_USER' => 'Client',
        'ROLE_TECHNICIAN' => 'Technicien',
        'ROLE_ADMIN' => 'Administrateur',
        'ROLE_SALES' => 'Cours de vente',
        'ROLE_PRICING' => 'Tarification',
        'ROLE_SUPERADMIN' => 'Super Admin'
    ];
    
    const PACK = [
        'DISABLE' => 'Inactif',
        'PACK_DEMO' => 'Pack DEMO',
        'PACK_LIGHT' => 'Pack LIGHT',
        'PACK_FULL' => 'Pack FULL',
        null => 'Aucun pack'
    ];
    
    const ISACTIVE = [
        1 => 'Activé',
        2 => 'En attente'
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id = null;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     *     message="Email Invalide"
     * )
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $status;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_activity;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $certification_phyto;
    
    /**
     * @ORM\OneToOne(targetEntity=Exploitation::class, mappedBy="users", cascade={"persist", "remove"})
     */
    private $exploitation;
    
    /**
     * @ORM\OneToMany(targetEntity=BsvUsers::class, mappedBy="customers")
     */
    private $bsvs;
    
    /**
     * @ORM\OneToMany(targetEntity=PanoramaSend::class, mappedBy="customers", orphanRemoval=true)
     */
    private $panoramas;
    
    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     */
    private $technician;
    
    /**
     * @ORM\OneToMany(targetEntity=PanoramaSend::class, mappedBy="sender")
     */
    private $panoramas_sent;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $pack = 'DISABLE';
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $reset = false;
    
    /**
     * @ORM\OneToMany(targetEntity=Tickets::class, mappedBy="technician", orphanRemoval=true)
     */
    private $tickets;
    
    /**
     * @ORM\OneToMany(targetEntity=Mix::class, mappedBy="user")
     */
    private $mixes;
    
    /**
     * @ORM\OneToMany(targetEntity=Orders::class, mappedBy="creator")
     */
    private $orders;
    
    /**
     * @ORM\ManyToOne(targetEntity=Warehouse::class, inversedBy="users")
     */
    private $warehouse;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $postalCode;
    
    /**
     * @ORM\OneToMany(targetEntity=PurchaseContract::class, mappedBy="creator", orphanRemoval=true)
     */
    private $purchaseContracts;
    
    /**
     * @ORM\OneToMany(targetEntity=Panorama::class, mappedBy="owner")
     */
    private $ownedPanoramas;
    
    public function __construct()
    {
        $this->bsvs              = new ArrayCollection();
        $this->panoramas         = new ArrayCollection();
        $this->panoramas_sent    = new ArrayCollection();
        $this->tickets           = new ArrayCollection();
        $this->mixes             = new ArrayCollection();
        $this->orders            = new ArrayCollection();
        $this->purchaseContracts = new ArrayCollection();
        $this->ownedPanoramas    = new ArrayCollection();
    }
    
    public function __serialize(): array
    {
        return [
            $this->id,
            $this->email,
            $this->password,
        ];
    }
    
    public function __unserialize(array $data): void
    {
        if(count($data) === 3) {
            [
                $this->id,
                $this->email,
                $this->password,
            ] = $data;
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }
    
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        
        return $this;
    }
    
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        
        return $this;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    public function getIdentity(): ?string
    {
        if(NULL != $this->getLastname() or NULL != $this->getFirstname()) {
            return $this->getLastname() . ' ' . $this->getFirstname();
        } else {
            return $this->getCompany();
        }
    }
    
    public function getCity(): ?string
    {
        return $this->city;
    }
    
    public function setCity(?string $city): self
    {
        $this->city = $city;
        
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): self
    {
        $this->password = $password;
        
        return $this;
    }
    
    public function getStatus($return = false): ?string
    {
        if($return) {
            return self::STATUS[$this->status];
        }
        return $this->status;
    }
    
    public function setStatus(string $status): self
    {
        $this->status = $status;
        
        return $this;
    }
    
    public function getLastActivity(): ?\DateTimeInterface
    {
        return $this->last_activity;
    }
    
    public function setLastActivity(?\DateTimeInterface $last_activity): self
    {
        $this->last_activity = $last_activity;
        
        return $this;
    }
    
    public function getCertificationPhyto(): ?string
    {
        return $this->certification_phyto;
    }
    
    public function setCertificationPhyto(?string $certification_phyto): self
    {
        $this->certification_phyto = $certification_phyto;
        
        return $this;
    }
    
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
    
    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return array($this->getStatus());
    }
    
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    
    /**
     * @inheritDoc
     */
    public function getUsername(): ?string
    {
        return $this->getEmail();
    }
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }
    
    public function __toString(): string
    {
        return $this->getId();
    }
    
    public function getExploitation(): ?Exploitation
    {
        return $this->exploitation;
    }
    
    public function setExploitation(Exploitation $exploitation): self
    {
        $this->exploitation = $exploitation;
        
        // set the owning side of the relation if necessary
        if($exploitation->getUsers() !== $this) {
            $exploitation->setUsers($this);
        }
        
        return $this;
    }
    
    /**
     * @return Collection|BsvUsers[]
     */
    public function getBsvs(): Collection
    {
        return $this->bsvs;
    }
    
    public function addBsv(BsvUsers $bsv): self
    {
        if(! $this->bsvs->contains($bsv)) {
            $this->bsvs[] = $bsv;
            $bsv->setCustomers($this);
        }
        
        return $this;
    }
    
    public function removeBsv(BsvUsers $bsv): self
    {
        if($this->bsvs->contains($bsv)) {
            $this->bsvs->removeElement($bsv);
            // set the owning side to null (unless already changed)
            if($bsv->getCustomers() === $this) {
                $bsv->setCustomers(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection|PanoramaSend[]
     */
    public function getPanoramas(): Collection
    {
        return $this->panoramas;
    }
    
    public function addPanorama(PanoramaSend $panorama): self
    {
        if(! $this->panoramas->contains($panorama)) {
            $this->panoramas[] = $panorama;
            $panorama->setCustomers($this);
        }
        
        return $this;
    }
    
    public function removePanorama(PanoramaSend $panorama): self
    {
        if($this->panoramas->contains($panorama)) {
            $this->panoramas->removeElement($panorama);
            // set the owning side to null (unless already changed)
            if($panorama->getCustomers() === $this) {
                $panorama->setCustomers(null);
            }
        }
        
        return $this;
    }
    
    public function getTechnician(): ?self
    {
        return $this->technician;
    }
    
    public function setTechnician(?self $technician): self
    {
        $this->technician = $technician;
        
        return $this;
    }
    
    /**
     * @return Collection|PanoramaSend[]
     */
    public function getPanoramasSent(): Collection
    {
        return $this->panoramas_sent;
    }
    
    public function addPanoramasSent(PanoramaSend $panoramasSent): self
    {
        if(! $this->panoramas_sent->contains($panoramasSent)) {
            $this->panoramas_sent[] = $panoramasSent;
            $panoramasSent->setSender($this);
        }
        
        return $this;
    }
    
    public function removePanoramasSent(PanoramaSend $panoramasSent): self
    {
        if($this->panoramas_sent->contains($panoramasSent)) {
            $this->panoramas_sent->removeElement($panoramasSent);
            // set the owning side to null (unless already changed)
            if($panoramasSent->getSender() === $this) {
                $panoramasSent->setSender(null);
            }
        }
        
        return $this;
    }
    
    public function getPack($return = false): ?string
    {
        if($return) {
            return self::PACK[$this->pack];
        }
        return $this->pack;
    }
    
    public function setPack(?string $pack): self
    {
        $this->pack = $pack;
        
        return $this;
    }
    
    public function getReset(): ?bool
    {
        return $this->reset;
    }
    
    public function setReset(bool $Reset): self
    {
        $this->reset = $Reset;
        
        return $this;
    }
    
    /**
     * @return Collection|Tickets[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
    
    public function addTicket(Tickets $ticket): self
    {
        if(! $this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setTechnician($this);
        }
        
        return $this;
    }
    
    public function removeTicket(Tickets $ticket): self
    {
        if($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if($ticket->getTechnician() === $this) {
                $ticket->setTechnician(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection|Mix[]
     */
    public function getMixes(): Collection
    {
        return $this->mixes;
    }
    
    public function addMix(Mix $mix): self
    {
        if(! $this->mixes->contains($mix)) {
            $this->mixes[] = $mix;
            $mix->setUser($this);
        }
        
        return $this;
    }
    
    public function removeMix(Mix $mix): self
    {
        if($this->mixes->contains($mix)) {
            $this->mixes->removeElement($mix);
            // set the owning side to null (unless already changed)
            if($mix->getUser() === $this) {
                $mix->setUser(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }
    
    public function addOrder(Orders $order): self
    {
        if(! $this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCreator($this);
        }
        
        return $this;
    }
    
    public function removeOrder(Orders $orders): self
    {
        if($this->orders->removeElement($orders)) {
            // set the owning side to null (unless already changed)
            if($orders->getCreator() === $this) {
                $orders->setCreator(null);
            }
        }
        
        return $this;
    }
    
    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }
    
    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;
        
        return $this;
    }
    
    public function getCompany(): ?string
    {
        return $this->company;
    }
    
    public function setCompany(?string $company): self
    {
        $this->company = $company;
        
        return $this;
    }
    
    public function getAddress(): ?string
    {
        return $this->address;
    }
    
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        
        return $this;
    }
    
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }
    
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        
        return $this;
    }
    
    /**
     * @return Collection|PurchaseContract[]
     */
    public function getPurchaseContracts(): Collection
    {
        return $this->purchaseContracts;
    }
    
    public function addPurchaseContract(PurchaseContract $purchaseContract): self
    {
        if(! $this->purchaseContracts->contains($purchaseContract)) {
            $this->purchaseContracts[] = $purchaseContract;
            $purchaseContract->setCreator($this);
        }
        
        return $this;
    }
    
    public function removePurchaseContract(PurchaseContract $purchaseContract): self
    {
        if($this->purchaseContracts->removeElement($purchaseContract)) {
            // set the owning side to null (unless already changed)
            if($purchaseContract->getCreator() === $this) {
                $purchaseContract->setCreator(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection|Panoramas[]
     */
    public function getOwnedPanoramas(): Collection
    {
        return $this->ownedPanoramas;
    }
    
    public function addOwnedPanorama(Panorama $ownedPanorama): self
    {
        if(! $this->ownedPanoramas->contains($ownedPanorama)) {
            $this->ownedPanoramas[] = $ownedPanorama;
            $ownedPanorama->setOwner($this);
        }
        
        return $this;
    }
    
    public function removeOwnedPanorama(Panorama $ownedPanorama): self
    {
        if($this->ownedPanoramas->removeElement($ownedPanorama)) {
            // set the owning side to null (unless already changed)
            if($ownedPanorama->getOwner() === $this) {
                $ownedPanorama->setOwner(null);
            }
        }
        
        return $this;
    }
}
