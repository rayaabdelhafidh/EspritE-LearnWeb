<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalleRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalleRepository")
 */


#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    /**
     *
     * @ORM\Column(name="salleId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $salleid;

  
   #[ORM\Column(length: 255)]
    private $bloc;

    #[ORM\Column]
    private $numerosalle;

    public function getSalleid(): ?int
    {
        return $this->salleid;
    }

    public function getBloc(): ?string
    {
        return $this->bloc;
    }

    public function setBloc(string $bloc): static
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getNumerosalle(): ?int
    {
        return $this->numerosalle;
    }

    public function setNumerosalle(int $numerosalle): static
    {
        $this->numerosalle = $numerosalle;

        return $this;
    }


}
