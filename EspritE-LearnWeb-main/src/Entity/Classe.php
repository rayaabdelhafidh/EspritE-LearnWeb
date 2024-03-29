<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClasseRepository;



/**
 * @ORM\Entity(repositoryClass="App\Repository\ClasseRepository")
 */
#[ORM\Entity(repositoryClass: ClasseRepository::class)]

class Classe
{

    public const NIVEAU_1A = '_1A';
    public const NIVEAU_2A = '_2A';
    public const NIVEAU_2P = '_2P';
    public const NIVEAU_3A = '_3A';
    public const NIVEAU_3B = '_3B';
    public const NIVEAU_4A = '_4A';
    public const NIVEAU_5A = '_5A';

    const FILIERE_TIC = 'TIC';
    const FILIERE_BUSINESS = 'Business';
    const FILIERE_GC = 'GC';
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idclasse=null;

    #[ORM\Column(length: 255)]

    private ?string $nomclasse=null;

    #[ORM\Column(length: 255)]

    private ?string  $filiere;

    #[ORM\Column(length: 255)]
    private ?int $nbreetudi;

    #[ORM\Column(length: 255)]

   
    private ?string $niveaux;

  
    #[ORM\OneToMany(mappedBy: 'idClasse', targetEntity: Presence::class)]
    private Collection $presence;
    private $idpresence = array();

    public function __construct()
    {
        $this->idpresence = new ArrayCollection();
        $this->presence = new ArrayCollection();
    }

    public function getIdclasse(): ?int
    {
        return $this->idclasse;
    }

    public function getNomclasse(): ?string
    {
        return $this->nomclasse;
    }

    public function setNomclasse(string $nomclasse): static
    {
        $this->nomclasse = $nomclasse;

        return $this;
    }

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(string $filiere): static
    {
        // Vérifie si la valeur passée est l'une des constantes définies pour la filière
        if (!in_array($filiere, [self::FILIERE_TIC, self::FILIERE_BUSINESS, self::FILIERE_GC])) {
            throw new \InvalidArgumentException("Filière invalide");
        }

        $this->filiere = $filiere;

        return $this;
    }

    public function getNbreetudi(): ?int
    {
        return $this->nbreetudi;
    }

    public function setNbreetudi(int $nbreetudi): static
    {
        $this->nbreetudi = $nbreetudi;

        return $this;
    }

    public function getNiveaux(): ?string
    {
        return $this->niveaux;
    }

    public function setNiveaux(string $niveaux): static
    {
        // Vérifie si la valeur passée est l'une des constantes définies pour les niveaux
        if (!in_array($niveaux, [
            self::NIVEAU_1A,
            self::NIVEAU_2A,
            self::NIVEAU_2P,
            self::NIVEAU_3A,
            self::NIVEAU_3B,
            self::NIVEAU_4A,
            self::NIVEAU_5A,
        ])) {
            throw new \InvalidArgumentException("Niveau invalide");
        }

        $this->niveaux = $niveaux;

        return $this;
    }
    


    /**
     * @return Collection<int, Presence>
     */
    public function getIdpresence(): Collection
    {
        return $this->idpresence;
    }

    public function addIdpresence(Presence $idpresence): static
    {
        if (!$this->idpresence->contains($idpresence)) {
            $this->idpresence->add($idpresence);
        }

        return $this;
    }

    public function removeIdpresence(Presence $idpresence): static
    {
        $this->idpresence->removeElement($idpresence);

        return $this;
    }

    /**
     * @return Collection<int, Presence>
     */
    public function getPresence(): Collection
    {
        return $this->presence;
    }

    public function addPresence(Presence $presence): static
    {
        if (!$this->presence->contains($presence)) {
            $this->presence[] = $presence;
            $presence->setNomclasse($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): static
    {
        if ($this->presence->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getIdclasse() === $this) {
                $presence->setIdclasse(null);
            }
        }
    
        return $this;
    }
    


    // public function removePresence(Presence $presence): static
    // {
    //     if ($this->presence->removeElement($presence)) {
    //         // set the owning side to null (unless already changed)
    //         if ($presence->getClasse() === $this) {
    //             $presence->setClasse(null);
    //         }
    //     }

    //     return $this;
    // }

}
