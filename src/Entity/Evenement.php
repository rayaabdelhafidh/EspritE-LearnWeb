<?php

namespace App\Entity;
use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass:EvenementRepository::class)]
class Evenement
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idevenement=null;

    #[ORM\Column(length: 155)]
    private ?string $nomevenement=null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateevenement=null;

    #[ORM\Column(length: 255)]
    private ?string $lieuevenement= null;

    
    #[ORM\Column]
    private ?float $prixevenement= null;


    #[ORM\Column(length: 255)]
    private ?string $afficheevenement= null;

    #[ORM\ManyToOne(inversedBy: 'evenement')]
    #[ORM\JoinColumn(name: 'club', referencedColumnName: 'idclub')]
    private ?Club $club= null;

    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Participant::class)]
    private Collection $evenement_participant;

    public function getIdevenement(): ?int
    {
        return $this->idevenement;
    }

    public function getNomevenement(): ?string
    {
        return $this->nomevenement;
    }

    public function setNomevenement(?string $nomevenement): static
    {
        $this->nomevenement = $nomevenement;

        return $this;
    }

    public function getDateevenement(): ?\DateTimeInterface
    {
        return $this->dateevenement;
    }

    public function setDateevenement(?\DateTimeInterface $dateevenement): static
    {
        $this->dateevenement = $dateevenement;

        return $this;
    }

    public function getLieuevenement(): ?string
    {
        return $this->lieuevenement;
    }

    public function setLieuevenement(?string $lieuevenement): static
    {
        $this->lieuevenement = $lieuevenement;

        return $this;
    }

    public function getPrixevenement(): ?float
    {
        return $this->prixevenement;
    }

    public function setPrixevenement(?float $prixevenement): static
    {
        $this->prixevenement = $prixevenement;

        return $this;
    }

    public function getAfficheevenement(): ?string
    {
        return $this->afficheevenement;
    }

    public function setAfficheevenement(?string $afficheevenement): static
    {
        $this->afficheevenement = $afficheevenement;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }


}