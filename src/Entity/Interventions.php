<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionsRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "recolte" = "Recolte",
 *     "binage" = "Binage",
 *     "labour" = "Labour",
 *     "epandange" = "Epandage",
 *     "semis" = "Semis"
 * })
 */

abstract class Interventions
{
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
     * @ORM\Column(type="datetime")
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

    public function setInterventionAt(\DateTimeInterface $intervention_at): self
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
     * @ORM\Column(type="integer", length=11, nullable=true)
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
     * @ORM\Column(type="integer", length=11, nullable=true)
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