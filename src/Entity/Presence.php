<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints\IsOnTheHour;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PresenceRepository::class)]
class Presence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[IsOnTheHour]
    #[Assert\NotBlank]

    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idEtudiants = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Classe $idClasse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSeance(): ?string
    {
        return $this->seance;
    }

    public function setSeance(?string $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getIdEtudiants(): ?string
    {
        return $this->idEtudiants;
    }

    public function setIdEtudiants(?string $idEtudiants): static
    {
        $this->idEtudiants = $idEtudiants;

        return $this;
    }

    public function getIdClasse(): ?Classe
    {
        return $this->idClasse;
    }

    public function setIdClasse(?Classe $idClasse): static
    {
        $this->idClasse = $idClasse;

        return $this;
    }
}
