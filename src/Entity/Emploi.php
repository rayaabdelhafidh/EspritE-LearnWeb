<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmploiRepository;
/**
 * Emploi
 *
 * @ORM\Table(name="emploi", indexes={@ORM\Index(name="salleId", columns={"salleId"}), @ORM\Index(name="classeId", columns={"classeId"})})
 *@ORM\Entity(repositoryClass="App\Repository\EmploiRepository")
 */

 #[ORM\Entity(repositoryClass: EmploiRepository::class)]
class Emploi
{
/**
                           
*
* @ORM\Column(name="emploiId", type="integer", nullable=false)
* @ORM\Id
* @ORM\GeneratedValue(strategy="IDENTITY")
*/
                           
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: "emploiId", type: "integer")]
private ?int $id;
                            
/**
* @var \DateTime|null
*
* @ORM\Column(name="premierDate", type="date", nullable=true)
*/
#[ORM\Column(type: "date")]
private ?\DateTimeInterface $premierdate;
                            
/**
* @var \DateTime|null
*
* @ORM\Column(name="dernierDate", type="date", nullable=true)
*/
#[ORM\Column(type: "date")]
private ?\DateTimeInterface $dernierdate;
                            
/**
* @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="emplois")
* @ORM\JoinColumn(name="salleId", referencedColumnName="salleId", nullable=false)
*/
                           
#[ORM\ManyToOne(targetEntity: Salle::class, inversedBy: 'emplois')]
#[ORM\JoinColumn(name: "salleId", referencedColumnName: "salleId", nullable: false)]
 private ?Salle $salle;
                           
/**
* @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="emplois")
* @ORM\JoinColumn(name="classeId", referencedColumnName="idClasse", nullable=false)
*/
#[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'emplois')]
#[ORM\JoinColumn(name: "classeId", referencedColumnName: "idClasse", nullable: false)]
private ?Classe $classe;
                           
/**
* @ORM\OneToMany(targetEntity=EmploiMatiere::class, mappedBy="emploi")
*/
#[ORM\OneToMany(mappedBy: 'emploi', targetEntity: EmploiMatiere::class)]
private Collection $emploiMatieres;
         
public function __construct()
{
$this->emploiMatieres = new ArrayCollection();
}
                           
                        
                           
public function getEmploiid(): ?int
{
return $this->emploiid;
}
                           
 public function getPremierdate(): ?\DateTimeInterface
{
 return $this->premierdate;
}
                           
public function setPremierdate(?\DateTimeInterface $premierdate): static
{
$this->premierdate = $premierdate;
                           
return $this;
}
                           
public function getDernierdate(): ?\DateTimeInterface
{
return $this->dernierdate;
}
                           
 public function setDernierdate(?\DateTimeInterface $dernierdate): static
{
$this->dernierdate = $dernierdate;
                           
return $this;
}
                           
public function getSalleid(): ?int
{
return $this->salleid;
}
                           
public function setSalleid(?int $salleid): static
{
$this->salleid = $salleid;
                           
  return $this;
}
                           
public function getClasseid(): ?int
 {
return $this->classeid;
}
                           
public function setClasseid(?int $classeid): static
 {
$this->classeid = $classeid;
                           
  return $this;
}
                           
public function getClasse(): ?Classe
 {
   return $this->classe;
}

public function setClasse(?Classe $classe): self
 {
 $this->classe = $classe;
                           
return $this;
}
                        
public function getId(): ?int
{
  return $this->id;
 }
                     
public function getSalle(): ?Salle
{
  return $this->salle;
}
                  
public function setSalle(?Salle $salle): static
{
$this->salle = $salle;
                  
 return $this;
}

public function addEmploiMatiere(EmploiMatiere $emploiMatiere): static
{
 if (!$this->emploiMatieres->contains($emploiMatiere)) {
 $this->emploiMatieres->add($emploiMatiere);
$emploiMatiere->setEmploi($this);
}
   
 return $this;
 }

public function removeEmploiMatiere(EmploiMatiere $emploiMatiere): static
{
 if ($this->emploiMatieres->removeElement($emploiMatiere)) {
                                       
if ($emploiMatiere->getEmploi() === $this) {
$emploiMatiere->setEmploi(null);
}
}

                                   return $this;
}

/**
     * @return Collection
     */
    public function getEmploiMatieres(): Collection
    {
        return $this->emploiMatieres;
    }
                           
}
