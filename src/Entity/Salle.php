<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salle
 *
 * @ORM\Table(name="salle")
 * @ORM\Entity
 */
class Salle
{
    /**
     * @var int
     *
     * @ORM\Column(name="salleId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $salleid;

    /**
     * @var string
     *
     * @ORM\Column(name="bloc", type="string", length=255, nullable=false)
     */
    private $bloc;

    /**
     * @var int
     *
     * @ORM\Column(name="numeroSalle", type="integer", nullable=false)
     */
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
