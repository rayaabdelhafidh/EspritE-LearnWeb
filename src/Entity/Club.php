<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert; 


#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idclub=null;

    #[ORM\Column(length: 155)]
    private ?string $nomclub=null;
    
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefondation=null;


    #[ORM\Column(length: 155)]
    private ?string $typeactivite=null;

  
    #[ORM\Column(length: 300)]
    private ?string $description=null;

    #[ORM\Column]
    private ?int $nbmembres=null;

    #[ORM\Column(type: "boolean")]
    private ?bool $active=null;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Evenement::class)]
    private Collection $evenement_club;
    

    public function __construct()
    {
        $this->evenement_club = new ArrayCollection();
    }

    public function initializeEvenementClub(): void
{        $this->evenement_club = new ArrayCollection();
}
    public function getIdclub(): ?int
    {
        return $this->idclub;
    }

    public function getNomclub(): ?string
    {
        return $this->nomclub;
    }

    public function setNomclub(string $nomclub):self 
    {
        $this->nomclub = $nomclub;

        return $this;
    }

    public function getDatefondation(): ?\DateTimeInterface
    {
        return $this->datefondation;
    }

    public function setDatefondation(\DateTimeInterface $datefondation):self
    {
        $this->datefondation = $datefondation;

        return $this;
    }

    public function getTypeactivite(): ?string
    {
        return $this->typeactivite;
    }

    public function setTypeactivite(string $typeactivite):self
    {
        $this->typeactivite = $typeactivite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description):self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbmembres(): ?int
    {
        return $this->nbmembres;
    }

    public function setNbmembres(int $nbmembres):self
    {
        $this->nbmembres = $nbmembres;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active):self
    {
        $this->active = $active;

        return $this;
    }

}