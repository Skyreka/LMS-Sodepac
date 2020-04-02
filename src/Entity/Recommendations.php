<?php

namespace App\Entity;

use Doctrine\DBAL\Schema\Index;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecommendationsRepository")
 */
class Recommendations
{
    const STATUS = [
        '0' => 'Draft',
        '1' => 'Saved',
        '2' => 'Sended'
    ];

    public function __construct()
    {
        $this->setCreateAt( new \DateTime() );
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exploitation", inversedBy="recommendations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exploitation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndexCultures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $culture;

    /**
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCulture(): ?IndexCultures
    {
        return $this->culture;
    }

    public function setCulture(?IndexCultures $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getStatus( $return = false ): ?int
    {
        if ($return) {
            return self::STATUS[$this->status];
        }
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }
}
