<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Schema\Index;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecommendationsRepository")
 */
class Recommendations
{
    const STATUS = [
        '0' => 'Draft',
        '1' => 'Create',
        '2' => 'Generate',
        '2' => 'Sended'
    ];

    public function __construct()
    {
        $this->setCreateAt( new \DateTime() );
        $this->recommendationProducts = new ArrayCollection();
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecommendationProducts", mappedBy="recommendation", orphanRemoval=true)
     */
    private $recommendationProducts;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $mention;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mention_txt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

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

    /**
     * @return Collection|RecommendationProducts[]
     */
    public function getRecommendationProducts(): Collection
    {
        return $this->recommendationProducts;
    }

    public function addRecommendationProduct(RecommendationProducts $recommendationProduct): self
    {
        if (!$this->recommendationProducts->contains($recommendationProduct)) {
            $this->recommendationProducts[] = $recommendationProduct;
            $recommendationProduct->setRecommendation($this);
        }

        return $this;
    }

    public function removeRecommendationProduct(RecommendationProducts $recommendationProduct): self
    {
        if ($this->recommendationProducts->contains($recommendationProduct)) {
            $this->recommendationProducts->removeElement($recommendationProduct);
            // set the owning side to null (unless already changed)
            if ($recommendationProduct->getRecommendation() === $this) {
                $recommendationProduct->setRecommendation(null);
            }
        }

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

    public function getMentionTxt(): ?string
    {
        return $this->mention_txt;
    }

    public function setMentionTxt(?string $mention_txt): self
    {
        $this->mention_txt = $mention_txt;

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
