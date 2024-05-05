<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalleRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;




#[ORM\Entity(repositoryClass: SalleRepository::class)]

class Salle
{


    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: "salleId")]
    private ?int $salleId=null;


    #[ORM\Column(length: 255, name:"bloc")]
    #[Assert\NotBlank(message:"bloc est requis.")]
    private ?string $bloc=null;


    #[ORM\Column(name:"numeroSalle")]
    #[Assert\NotBlank(message:"numéro de salle est requis.")]
    #[Assert\Length(
        exactMessage: "numéro de salle doit contenir 3 caractéres",
        min: 3,
        max: 3
    )]
    #[Assert\Type(
        type: 'integer',
        message: "numéro de salle doit étre un numéro"
    )]
    private ?int $numeroSalle=null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Emploi::class)]
    private Collection $emplois;

    public function __construct()
    {
        $this->emplois = new ArrayCollection();
    }

    public function getSalleId(): ?int
    {
        return $this->salleId;
    }

    public function getBloc(): ?string
    {
        return $this->bloc;
    }

    public function setBloc(string $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getNumeroSalle(): ?int
    {
        return $this->numeroSalle;
    }

    public function setNumeroSalle(int $numeroSalle): static
    {
        $this->numeroSalle = $numeroSalle;

        return $this;
    }

 /**
     * @return Collection|Emploi[]
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    public function addEmploi(Emploi $emploi): self
    {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois[] = $emploi;
            $emploi->setSalle($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            if ($emploi->getSalle() === $this) {
                $emploi->setSalle(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->bloc . ' - ' . $this->numeroSalle;
    }

}
