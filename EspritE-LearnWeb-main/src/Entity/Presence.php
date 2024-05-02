<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PresenceRepository;
use PhpParser\Node\Expr\Cast\String_;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]

class Presence
{
    public const SEANCE_S1 = 'S1';
    public const SEANCE_S2 = 'S2';
    public const SEANCE_S1ETS2 = 'S1ETS2';
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idpresence = null;

    #[ORM\Column(length: 255)]
    private ?\DateTimeInterface $date;

    #[ORM\Column(length: 255)]
    private ?string $seance;

    #[ORM\ManyToOne(targetEntity: Classe::class)]
    #[ORM\JoinColumn(name: "idClasse", referencedColumnName: "idClasse")]
    private ?Classe $idClasse;
    
    
    #[ORM\Column(name: "nomClasse")]
    private ?string $nomClasse;
    public function getIdpresence(): ?int
    {
        return $this->idpresence;
    }
    public function getNomClasse(): ?string
    {
        return $this->nomClasse;
    }
    public function setNomClasse(?string $nomClasse): self
    {
        $this->nomClasse = $nomClasse;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getSeance(): ?string
    {
        return $this->seance;
    }

    public function setSeance(string $seance): self
    {
        // Vérifie si la valeur passée est l'une des constantes définies pour l'enum Seance
        if (!in_array($seance, [
            self::SEANCE_S1,
            self::SEANCE_S2,
            self::SEANCE_S1ETS2,
        ])) {
            throw new \InvalidArgumentException("Seance invalide");
        }

        $this->seance = $seance;
        return $this;
    }

    public function getIdClasse(): ?Classe
    {
        return $this->idClasse;
    }
    

    public function setIdClasse(?Classe $idClasse): self
    {
        $this->idClasse = $idClasse;
        return $this;
    }
}