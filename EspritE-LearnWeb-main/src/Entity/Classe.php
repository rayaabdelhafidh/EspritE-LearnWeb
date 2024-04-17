<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClasseRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Type;


#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    public const NIVEAU_1A = '1A';
    public const NIVEAU_2A = '2A';
    public const NIVEAU_2P = '2P';
    public const NIVEAU_3A = '3A';
    public const NIVEAU_3B = '3B';
    public const NIVEAU_4A = '4A';
    public const NIVEAU_5A = '5A';

    const FILIERE_TIC = 'TIC';
    const FILIERE_BUSINESS = 'Business';
    const FILIERE_GC = 'GC';
   
 
    
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer', name:"idClasse")]
private ?int $idClasse ;




    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir le nom du classe')]
    #[Assert\Regex(
        pattern: '/^(?:[1-5][AB]|[2][ABP])(?:[1-9]|[12]\d|30)$/',
        message: 'Le format du nom de la classe n\'est pas valide.'
    )]
    private ?string $nomClasse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez selectionner un filiere')]

    private ?string  $filiere = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez fixer un nombre des etudiants')]
    #[Assert\LessThanOrEqual(
        value: 25, message: 'Le nombre des étudiants ne doit pas dépasser 25.'
    )]
    private ?int $nbreetudi = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez selectionner un niveau de classe')]
    private ?string $niveaux = null;

    #[ORM\OneToMany(mappedBy: 'idClasse', targetEntity: Presence::class)]
    private Collection $presence;

    public function __construct()
    {
        $this->presence = new ArrayCollection();
    }

    public function getIdClasse(): ?int
    {
        return $this->idClasse;
    }

    public function getNomClasse(): ?string
    {
        return $this->nomClasse;
    }

    public function setNomClasse(string $nomClasse): static
    {
        $this->nomClasse = $nomClasse;

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
    public function getPresence(): Collection
    {
        return $this->presence;
    }

    public function addPresence(Presence $presence): static
    {
        if (!$this->presence->contains($presence)) {
            $this->presence[] = $presence;
            $presence->setIdClasse($this);
        }

        return $this;
    }

}
