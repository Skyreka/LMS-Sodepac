<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionsRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "recolte" = "Recolte",
 *     "binage" = "Binage",
 *     "labour" = "Labour",
 *     "fertilisant" = "Fertilisant",
 *     "phyto" = "Phyto",
 *     "epandange" = "Epandage",
 *     "semis" = "Semis"
 * })
 */

abstract class Interventions
{
    public function __construct()
    {
        $this->intervention_at = new \DateTime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cultures", inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $culture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $intervention_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCulture(): ?Cultures
    {
        return $this->culture;
    }

    public function setCulture(?Cultures $culture): self
    {
        $this->culture = $culture;

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

    public function getInterventionAt(): ?\DateTimeInterface
    {
        return $this->intervention_at;
    }

    public function setInterventionAt($intervention_at): self
    {
        $this->intervention_at = $intervention_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
}

/**
 * @ORM\Entity()
 * @ORM\Table(name="recolte")
 */
class Recolte extends Interventions
{
    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $rendement;

    /**
     * @return mixed
     */
    public function getRendement()
    {
        return $this->rendement;
    }

    /**
     * @param mixed $quantity
     * @return Recolte
     */
    public function setRendement($quantity): self
    {
        $this->rendement = $quantity;

        return $this;
    }
}
/**
 * @ORM\Entity()
 * @ORM\Table(name="binage")
 */
class Binage extends Interventions
{

}
/**
 * @ORM\Entity()
 * @ORM\Table(name="labour")
 */
class Labour extends Interventions
{

}

/**
 * @ORM\Entity()
 * @ORM\Table(name="int_epandage")
 */
class Epandage extends Interventions
{
    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexEffluents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $effluent;

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return Epandage
     */
    public function setQuantity($quantity): Epandage
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEffluent()
    {
        return $this->effluent;
    }

    /**
     * @param mixed $effluent
     */
    public function setEffluent($effluent): void
    {
        $this->effluent = $effluent;
    }
}

/**
 * @ORM\Entity()
 * @ORM\Table(name="int_semis")
 */
class Semis extends Interventions
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $objective;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * @param mixed $objective
     */
    public function setObjective($objective): void
    {
        $this->objective = $objective;
    }
}

/**
 * @ORM\Entity()
 * @ORM\Table(name="int_fertilisant")
 */
class Fertilisant extends Interventions
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $n;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $p;

    /**
     * @return mixed
     */
    public function getP()
    {
        return $this->p;
    }

    /**
     * @param mixed $p
     */
    public function setP($p): void
    {
        $this->p = $p;
    }

    /**
     * @return mixed
     */
    public function getK()
    {
        return $this->k;
    }

    /**
     * @param mixed $k
     */
    public function setK($k): void
    {
        $this->k = $k;
    }

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $k;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products")
     */
    private $product;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $reliquat;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getReliquat()
    {
        return $this->reliquat;
    }

    /**
     * @param mixed $reliquat
     */
    public function setReliquat($reliquat): void
    {
        $this->reliquat = $reliquat;
    }

    /**
     * @return mixed
     */
    public function getN()
    {
        return $this->n;
    }

    /**
     * @param mixed $n
     */
    public function setN($n): void
    {
        $this->n = $n;
    }
}

/**
 * @ORM\Entity()
 * @ORM\Table(name="int_phyto")
 */
class Phyto extends Interventions
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products")
     */
    private $product;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", length=11, nullable=true)
     */
    private $dose = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterventionsProducts", mappedBy="intervention")
     */
    private $interventionsProducts;

    /**
     * Function to get IFT
     * @return string
     */
    public function getIft()
    {

        // IF MAKE CHANGE HERE CHANGE ON INTERVENTIONPRODUCT
        $doseApplique = $this->getQuantity() / $this->getCulture()->getSize();
        $doseHomologue = $this->getDose();
        $surfaceTraite = $this->getCulture()->getRealSize();
        $surfaceTotal = $this->getCulture()->getSize();

        //-- Display only if have all value
        if ($doseApplique != null &&
            $doseHomologue != null &&
            $surfaceTraite != null &&
            $surfaceTotal != null) {
            $result = ( $doseApplique / $doseHomologue) * ($surfaceTraite / $surfaceTotal);
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * @return Collection|InterventionsProducts[]
     */
    public function getInterventionsProducts(): Collection
    {
        return $this->interventionsProducts;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getReliquat()
    {
        return $this->reliquat;
    }

    /**
     * @param mixed $reliquat
     */
    public function setReliquat($reliquat): void
    {
        $this->reliquat = $reliquat;
    }

    /**
     * @return mixed
     */
    public function getDose()
    {
        return $this->dose;
    }

    /**
     * @param mixed $dose
     */
    public function setDose($dose): void
    {
        $this->dose = $dose;
    }
}
