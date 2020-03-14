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
        'ROLE_ADMIN' => 'Administrateur'
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
     * @ORM\Column(type="string", length=30)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30)
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
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $technician;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Exploitation", mappedBy="users", cascade={"persist", "remove"})
     */
    private $exploitation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Panoramas", mappedBy="customers")
     */
    private $panoramas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panoramas", mappedBy="technician")
     */
    private $panoramasOwn;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BsvUsers", mappedBy="customers")
     */
    private $bsvs;

    public function __construct()
    {
        $this->bsvs = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTechnician(): ?int
    {
        return $this->technician;
    }

    public function setTechnician($technician): self
    {
        $this->technician = $technician;

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
     * @return Collection|Panoramas[]
     */
    public function getPanoramas(): Collection
    {
        return $this->panoramas;
    }

    public function addPanorama(Panoramas $panorama): self
    {
        if (!$this->panoramas->contains($panorama)) {
            $this->panoramas[] = $panorama;
            $panorama->setTechnician($this);
        }

        return $this;
    }

    public function removePanorama(Panoramas $panorama): self
    {
        if ($this->panoramas->contains($panorama)) {
            $this->panoramas->removeElement($panorama);
            // set the owning side to null (unless already changed)
            if ($panorama->getTechnician() === $this) {
                $panorama->setTechnician(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPanoramasOwn()
    {
        return $this->panoramasOwn;
    }

    /**
     * @param mixed $panoramasOwn
     */
    public function setPanoramasOwn($panoramasOwn): void
    {
        $this->panoramasOwn = $panoramasOwn;
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
}
