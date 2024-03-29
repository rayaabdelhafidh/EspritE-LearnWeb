<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmploiRepository;
/**
 * Emploi
 *
 * @ORM\Table(name="emploi", indexes={@ORM\Index(name="salleId", columns={"salleId"}), @ORM\Index(name="classeId", columns={"classeId"})})
 * @ORM\Entity
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
     #[ORM\Column]
    private ?int $emploiid;


     #[ORM\Column(type: "date")]
    private ?date $premierdate;


     #[ORM\Column(type: "date")]
    private ?date $dernierdate = null;

    #[ORM\ManyToOne(inversedBy: 'Emploi')]
    private ?Salle $salleid = null;

   #[ORM\ManyToOne(inversedBy: 'Emploi')]
    private ?Classe $classeid = null;

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


}
