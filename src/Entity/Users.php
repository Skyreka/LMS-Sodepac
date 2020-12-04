<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity("email")
 */
class Users implements UserInterface, \Serializable
{
    const STATUS = [
        'ROLE_USER' => 'Client',
        'ROLE_TECHNICIAN' => 'Technicien',
        'ROLE_ADMIN' => 'Administrateur',
        'ROLE_SALES' => 'Cours de vente'
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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\OneToOne(targetEntity="App\Entity\Exploitation", mappedBy="users", cascade={"persist", "remove"})
     */
    private $exploitation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BsvUsers", mappedBy="customers")
     */
    private $bsvs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PanoramaUser", mappedBy="customers", orphanRemoval=true)
     */
    private $panoramas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users")
     */
    private $technician;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PanoramaUser", mappedBy="sender")
     */
    private $panoramas_sent;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $pack = 'DISABLE';

    /**
     * @ORM\Column(type="boolean")
     */
    private $reset = 0;

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

    public function __construct()
    {
        $this->bsvs = new ArrayCollection();
        $this->panoramas = new ArrayCollection();
        $this->panoramas_sent = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->mixes = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
        return $this->getLastname().' '.$this->getFirstname();
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

    public function getStatus( $return = false ): ?string
    {
        if ($return) {
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
    public function getRoles()
    {
        return array($this->getStatus());
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
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
        if ($exploitation->getUsers() !== $this) {
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
        if (!$this->bsvs->contains($bsv)) {
            $this->bsvs[] = $bsv;
            $bsv->setCustomers($this);
        }

        return $this;
    }

    public function removeBsv(BsvUsers $bsv): self
    {
        if ($this->bsvs->contains($bsv)) {
            $this->bsvs->removeElement($bsv);
            // set the owning side to null (unless already changed)
            if ($bsv->getCustomers() === $this) {
                $bsv->setCustomers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PanoramaUser[]
     */
    public function getPanoramas(): Collection
    {
        return $this->panoramas;
    }

    public function addPanorama(PanoramaUser $panorama): self
    {
        if (!$this->panoramas->contains($panorama)) {
            $this->panoramas[] = $panorama;
            $panorama->setCustomers($this);
        }

        return $this;
    }

    public function removePanorama(PanoramaUser $panorama): self
    {
        if ($this->panoramas->contains($panorama)) {
            $this->panoramas->removeElement($panorama);
            // set the owning side to null (unless already changed)
            if ($panorama->getCustomers() === $this) {
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
     * @return Collection|PanoramaUser[]
     */
    public function getPanoramasSent(): Collection
    {
        return $this->panoramas_sent;
    }

    public function addPanoramasSent(PanoramaUser $panoramasSent): self
    {
        if (!$this->panoramas_sent->contains($panoramasSent)) {
            $this->panoramas_sent[] = $panoramasSent;
            $panoramasSent->setSender($this);
        }

        return $this;
    }

    public function removePanoramasSent(PanoramaUser $panoramasSent): self
    {
        if ($this->panoramas_sent->contains($panoramasSent)) {
            $this->panoramas_sent->removeElement($panoramasSent);
            // set the owning side to null (unless already changed)
            if ($panoramasSent->getSender() === $this) {
                $panoramasSent->setSender(null);
            }
        }

        return $this;
    }

    public function getPack( $return = false ): ?string
    {
        if ($return) {
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
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setTechnician($this);
        }

        return $this;
    }

    public function removeTicket(Tickets $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getTechnician() === $this) {
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
        if (!$this->mixes->contains($mix)) {
            $this->mixes[] = $mix;
            $mix->setUser($this);
        }

        return $this;
    }

    public function removeMix(Mix $mix): self
    {
        if ($this->mixes->contains($mix)) {
            $this->mixes->removeElement($mix);
            // set the owning side to null (unless already changed)
            if ($mix->getUser() === $this) {
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
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCreator($this);
        }

        return $this;
    }

    public function removeOrder(Orders $orders): self
    {
        if ($this->orders->removeElement($orders)) {
            // set the owning side to null (unless already changed)
            if ($orders->getCreator() === $this) {
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
}
