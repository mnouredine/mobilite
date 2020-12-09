<?php

namespace App\Entity;

use App\Repository\ArretRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArretRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Arret
{
    use Timestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Itineraire", inversedBy="itineraire")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $itineraire;

    /**
     * @ORM\ManyToOne(targetEntity="Passage", inversedBy="passage")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $passage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $temps;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemps(): ?string
    {
        return $this->temps;
    }

    public function setTemps(string $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
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

    public function getPassage(): ?Passage
    {
        return $this->passage;
    }

    public function setPassage(?Passage $passage): self
    {
        $this->passage = $passage;

        return $this;
    }
}
