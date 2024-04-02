<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\MatiereRepository")
 */

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    
      /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $idm = null;

    /**
     * @ORM\Column(length=255)
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Nom matiere  is required")]
    #[Assert\Length(min:2,minMessage:"Votre nom de matiere ne contient pas au minimum {{ limit }} caractères.")]
    private ?string $nomm = null;

    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"idenseignant is required")]
    private ?int $idenseignant = null;

    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"nbrheure is required")]
    #[Assert\GreaterThan(value:0 ,message:"nombre dheure doit être un nombre positif.")]
    private ?int $nbrheure = null;

  
     /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"coefficient is required")]
    #[Assert\GreaterThan(value:0 ,message:"coefficient doit être un nombre positif.")]
    private ?int $coefficient = null;

   /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"semester is required")]
    #[Assert\GreaterThan(value:0 ,message:"semester doit être un nombre positif.")]
    private ?int $semester = null;


    /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"credit  is required")]
    #[Assert\GreaterThan(value:0 ,message:"credit doit être un nombre positif.")]
    private ?int $credit = null;

     /**
     * @var \Plandetude
     *
     * @ORM\ManyToOne(targetEntity="Plandetude")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlanDetude", referencedColumnName="id")
     * })
     */
    
    #[ORM\ManyToOne(inversedBy: 'Matiere')]
    
    private ?Plandetude $idplandetude = null;

  /**
   * @ORM\OneToMany(targetEntity="Cour",mappedBy="Matiere")
   */

    #[ORM\OneToMany(mappedBy: 'Matiere', targetEntity: Cour::class)]
    private Collection $Cour;
    

    public function getIdm(): ?int
    {
        return $this->idm;
    }

    public function getNomm(): ?string
    {
        return $this->nomm;
    }

    public function setNomm(string $nomm): static
    {
        $this->nomm = $nomm;

        return $this;
    }

    public function getIdenseignant(): ?int
    {
        return $this->idenseignant;
    }

    public function setIdenseignant(int $idenseignant): static
    {
        $this->idenseignant = $idenseignant;

        return $this;
    }

    public function getNbrheure(): ?int
    {
        return $this->nbrheure;
    }

    public function setNbrheure(int $nbrheure): static
    {
        $this->nbrheure = $nbrheure;

        return $this;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getSemester(): ?int
    {
        return $this->semester;
    }

    public function setSemester(int $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getIdplandetude(): ?Plandetude
{
    return $this->idplandetude;
}

public function setIdplandetude(?Plandetude $idplandetude): static
{
    $this->idplandetude = $idplandetude;

    return $this;
}

 
     /** 
     * @return Collection<int, Cour>
     */
    public function getCour(): Collection
    {
        return $this->Cour;
    }
 public function __construct()
    {
        $this->Cour = new ArrayCollection();
    }
   
}
