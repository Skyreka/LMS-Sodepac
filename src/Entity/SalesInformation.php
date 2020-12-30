<?php

namespace App\Entity;

use App\Repository\SalesInformationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalesInformationRepository::class)
 */
class SalesInformation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $top_message;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer_message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopMessage(): ?string
    {
        return $this->top_message;
    }

    public function setTopMessage(?string $top_message): self
    {
        $this->top_message = $top_message;

        return $this;
    }

    public function getFooterMessage(): ?string
    {
        return $this->footer_message;
    }

    public function setFooterMessage(?string $footer_message): self
    {
        $this->footer_message = $footer_message;

        return $this;
    }
}
