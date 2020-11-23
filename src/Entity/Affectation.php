<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AffectationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Affectation
{
    use Timestamps;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Itineraire", inversedBy="itineraire")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $itineraire;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Bus", inversedBy="bus")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $bus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getItineraire(): ?Itineraire
    {
        return $this->itineraire;
    }

    public function setItineraire(?Itineraire $itineraire): self
    {
        $this->itineraire = $itineraire;

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(?Bus $bus): self
    {
        $this->bus = $bus;

        return $this;
    }
}
