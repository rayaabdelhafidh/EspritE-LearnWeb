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
    private ?int $salleId=null;

      /**
     * @var string
     *
     * @ORM\Column(name="bloc", type="string", length=255, nullable=false)
     */
  
    private ?string $bloc=null;

       /**
     * @var int
     *
     * @ORM\Column(name="numeroSalle", type="integer", nullable=false)
     */
    #[ORM\Column]
    private ?int $numeroSalle=null;

    public function getSalleId(): ?int
    {
        return $this->salleId;
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

    public function getNumeroSalle(): ?int
    {
        return $this->numeroSalle;
    }

    public function setNumeroSalle(int $numeroSalle): static
    {
        $this->numeroSalle = $numeroSalle;

        return $this;
    }


}
