<?php

namespace App\Entity;


use App\Repository\CourRepository;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity(repositoryClass="App\Repository\CourRepository")
 */
#[ORM\Entity(repositoryClass: CourRepository::class)]
class Cour
{
     /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $id = null;

    /**
     * @ORM\Column(length=500)
     */
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    
    /**
     * @ORM\Column(length=500)
     */
    #[ORM\Column(length: 500)]
    private ?string $description = null;
/**
     * @ORM\Column(type="integer")
     */
    
    #[ORM\Column]
    private ?int $duree = null;

    /**
     * @ORM\Column(length=500)
     */
    #[ORM\Column(length: 500)]
    private ?string $objectif = null;

    /**
     * @ORM\Column(length=300)
     */
    #[ORM\Column(length: 300)]
    private ?string $image = null;

   /**
     * @ORM\Column(length=500)
     */
    #[ORM\Column(length: 500)]
    private ?string $courspdfurl = null;

    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    private ?int $note = null;


   /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    private ?int $nblike = null;

    
    
   
/**
 * @var \Matiere
 *
 * @ORM\ManyToOne(targetEntity="Matiere")
 * @ORM\JoinColumns({
 *   @ORM\JoinColumn(name="idMatiere", referencedColumnName="idm")
 * })
 */
#[ORM\ManyToOne(targetEntity: Matiere::class)]
#[ORM\JoinColumn(name: 'idMatiere', referencedColumnName: 'idm')]
private ?Matiere $idmatiere = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCourspdfurl(): ?string
    {
        return $this->courspdfurl;
    }

    public function setCourspdfurl(string $courspdfurl): static
    {
        $this->courspdfurl = $courspdfurl;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getNblike(): ?int
    {
        return $this->nblike;
    }

    public function setNblike(int $nblike): static
    {
        $this->nblike = $nblike;

        return $this;
    }

    public function getIdmatiere(): ?Matiere
    {
        return $this->idmatiere;
    }

    public function setIdmatiere(?Matiere $idmatiere): static
    {
        $this->idmatiere = $idmatiere;

        return $this;
    }


}