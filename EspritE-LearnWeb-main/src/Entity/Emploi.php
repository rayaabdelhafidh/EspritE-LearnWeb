<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Emploi
 *
 * @ORM\Table(name="emploi", indexes={@ORM\Index(name="salleId", columns={"salleId"}), @ORM\Index(name="classeId", columns={"classeId"})})
 * @ORM\Entity
 */
class Emploi
{
    /**
     * @var int
     *
     * @ORM\Column(name="emploiId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emploiid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="premierDate", type="date", nullable=true)
     */
    private $premierdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dernierDate", type="date", nullable=true)
     */
    private $dernierdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="salleId", type="integer", nullable=true)
     */
    private $salleid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="classeId", type="integer", nullable=true)
     */
    private $classeid;

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
