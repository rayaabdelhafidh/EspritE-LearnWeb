<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlandetudeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PlandetudeRepository")
 */
#[ORM\Entity(repositoryClass: PlandetudeRepository::class)]
class Plandetude
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

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message:"Nom programme  is required")]
    #[Assert\Length(min:2,minMessage:"le nom programme ne contient pas au minimum {{ limit }} caractères.")]
    private ?string $nomprogramme = null;

     /**
     * @ORM\Column(length=250)
     */
    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message:"Niveau  is required")]
    #[Assert\Length(min:2,minMessage:"le niveau ne contient pas au minimum {{ limit }} caractères.")]
    private ?string $niveau = null;
    
/**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
   
    private ?int $dureetotal = null;

     /**
     * @ORM\Column(type="integer")
     */
    #[ORM\Column]
    
    private ?int $creditsrequistotal = null;


  /**
   * @ORM\OneToMany(targetEntity="Matiere",mappedBy="Plandetude")
   */
    #[ORM\OneToMany(mappedBy: 'Plandetude', targetEntity: Matiere::class)]
    private Collection $Matiere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomprogramme(): ?string
    {
        return $this->nomprogramme;
    }

    public function setNomprogramme(string $nomprogramme): static
    {
        $this->nomprogramme = $nomprogramme;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getDureetotal(): ?int
    {
        return $this->dureetotal;
    }

    public function setDureetotal(int $dureetotal): static
    {
        $this->dureetotal = $dureetotal;

        return $this;
    }

    public function getCreditsrequistotal(): ?int
    {
        return $this->creditsrequistotal;
    }

    public function setCreditsrequistotal(int $creditsrequistotal): static
    {
        $this->creditsrequistotal = $creditsrequistotal;

        return $this;
    }
 
     /** 
     * @return Collection<int, Matiere>
     */
    public function getMatiere(): Collection
    {
        return $this->Matiere;
    }

}
