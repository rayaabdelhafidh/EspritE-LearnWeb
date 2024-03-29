<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlandetudeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

   
    #[ORM\Column(length: 500)]
    private ?string $nomprogramme = null;

    
    #[ORM\Column(length: 250)]
    private ?string $niveau = null;
    

    #[ORM\Column]
    private ?int $dureetotal = null;

    
    #[ORM\Column]
    private ?int $creditsrequistotal = null;

    #[ORM\OneToMany(mappedBy: 'Plandetude', targetEntity: Matiere::class)]
    private Collection $Matiere;

    public function __construct()
    {
        $this->Matiere = new ArrayCollection();
    }

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

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->Matiere->contains($matiere)) {
            $this->Matiere->add($matiere);
            $matiere->setPlandetude($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->Matiere->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getPlandetude() === $this) {
                $matiere->setPlandetude(null);
            }
        }

        return $this;
    }

}
