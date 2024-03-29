<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 */
#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
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
    private ?bool $active=true;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Evenement::class)]
    private Collection $evenement_club;
    

    public function __construct()
    {
        $this->evenement_club = new ArrayCollection();
    }

    public function getIdclub(): ?int
    {
        return $this->idclub;
    }

    public function getNomclub(): ?string
    {
        return $this->nomclub;
    }

    public function setNomclub(?string $nomclub)
    {
        $this->nomclub = $nomclub;

        return $this;
    }

    public function getDatefondation(): ?date
    {
        return $this->datefondation;
    }

    public function setDatefondation(?date $datefondation)
    {
        $this->datefondation = $datefondation;

        return $this;
    }

    public function getTypeactivite(): ?string
    {
        return $this->typeactivite;
    }

    public function setTypeactivite(?string $typeactivite)
    {
        $this->typeactivite = $typeactivite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function getNbmembres(): ?int
    {
        return $this->nbmembres;
    }

    public function setNbmembres(?int $nbmembres)
    {
        $this->nbmembres = $nbmembres;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenementClub(): Collection
    {
        return $this->evenement_club;
    }

    public function addEvenementClub(Evenement $evenementClub): static
    {
        if (!$this->evenement_club->contains($evenementClub)) {
            $this->evenement_club->add($evenementClub);
            $evenementClub->setClub($this);
        }

        return $this;
    }

    public function removeEvenementClub(Evenement $evenementClub): static
    {
        if ($this->evenement_club->removeElement($evenementClub)) {
            // set the owning side to null (unless already changed)
            if ($evenementClub->getClub() === $this) {
                $evenementClub->setClub(null);
            }
        }

        return $this;
    }


}