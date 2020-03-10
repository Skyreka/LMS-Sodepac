<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnalyseRepository")
 */
class Analyse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ilots", inversedBy="analyses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ilot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exploitation", inversedBy="analyses")
     */
    private $exploitation;

    /**
     * @ORM\Column(type="integer")
     */
    private $measure;

    /**
     * @ORM\Column(type="datetime")
     */
    private $intervention_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIlot(): ?Ilots
    {
        return $this->ilot;
    }

    public function setIlot(?Ilots $ilot): self
    {
        $this->ilot = $ilot;

        return $this;
    }

    public function getExploitation(): ?Exploitation
    {
        return $this->exploitation;
    }

    public function setExploitation(?Exploitation $exploitation): self
    {
        $this->exploitation = $exploitation;

        return $this;
    }

    public function getMeasure(): ?int
    {
        return $this->measure;
    }

    public function setMeasure(int $measure): self
    {
        $this->measure = $measure;

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
}
