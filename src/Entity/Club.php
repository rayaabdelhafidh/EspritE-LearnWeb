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
    #[Assert\NotBlank(message: "ID cannot be blank")]
    private ?int $idclub=null;

    /**
     * @var string
     *
     * @ORM\Column(name="nomclub", type="string", length=155, nullable=false)
     */
    #[ORM\Column(length: 155)]
    #[Assert\NotBlank(message: "club name cannot be blank")]
    #[Assert\Length(
        max: 155,
        maxMessage: "the club name cannot be longer than {{ limit }} characters"
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9]+$/",
        message: "the club name can only contain numbers and alphabets"
    )]
    private ?string $nomclub=null;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datefondation", type="date", nullable=true)
     */
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "date cannot be blank")]
    private ?\DateTimeInterface $datefondation=null;

/**
     * @var string
     *
     * @ORM\Column(name="typeactivite", type="string", length=155, nullable=false)
     */
    #[ORM\Column(length: 155)]
    #[Assert\NotBlank(message: "activity type cannot be blank")]
    private ?string $typeactivite=null;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=false)
     */
    #[ORM\Column(length: 300)]
    #[Assert\NotBlank(message: "description cannot be blank")]

    private ?string $description=null;

    /**
     * @var int
     *
     * @ORM\Column(name="nbmembres", type="integer", nullable=false)
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "number cannot be blank")]
    private ?int $nbmembres=null;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    #[ORM\Column(type: "boolean")]
    private ?bool $active=null;

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