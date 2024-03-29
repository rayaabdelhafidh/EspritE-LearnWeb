<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Repository\PresenceRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PresenceRepository")
 */
#[ORM\Entity(repositoryClass: PresenceRepository::class)]
class Presence
{

    public const SEANCE_S1 = 'S1';
    public const SEANCE_S2 = 'S2';
    public const SEANCE_S1ETS2 = 'S1ETS2';





   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
    private ?int $idpresence=null;

    #[ORM\Column(length: 255)]
    private ?\DateTimeInterface $date;

    #[ORM\Column(length: 255)]
    private $seance;

  #[ORM\ManyToOne(inversedBy:'Classe')]
    private  ?Classe $nomclasse=null;

    #[ORM\ManyToOne(inversedBy:'Classe')]
    private  ?Classe $idclasse=null;

    public function getIdpresence(): ?int
    {
        return $this->idpresence;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSeance(): ?string
    {
        return $this->seance;
    }

    public function setSeance(string $seance): static
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

    public function getNomclasse(): ?Classe
    {
        return $this->nomclasse;
    }

    public function setNomclasse(?Classe $nomclasse): static
    {
        $this->nomclasse = $nomclasse;

        return $this;
    }

    public function getIdclasse(): ?Classe
    {
        return $this->idclasse;
    }

    public function setIdclasse(?Classe $idclasse): static
    {
        $this->idclasse = $idclasse;

        return $this;
    }

   




}
