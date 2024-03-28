<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmploiMatiere
 *
 * @ORM\Table(name="emploi_matiere", indexes={@ORM\Index(name="matiereId", columns={"matiereId"})})
 * @ORM\Entity
 */
class EmploiMatiere
{
    /**
     * @var int
     *
     * @ORM\Column(name="emploiId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $emploiid;

    /**
     * @var int
     *
     * @ORM\Column(name="matiereId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $matiereid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time", nullable=false)
     */
    private $starttime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="time", nullable=false)
     */
    private $endtime;

    /**
     * @var string
     *
     * @ORM\Column(name="dayOfWeek", type="string", length=20, nullable=false)
     */
    private $dayofweek;

    public function getEmploiid(): ?int
    {
        return $this->emploiid;
    }

    public function getMatiereid(): ?int
    {
        return $this->matiereid;
    }

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTimeInterface $starttime): static
    {
        $this->starttime = $starttime;

        return $this;
    }

    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTimeInterface $endtime): static
    {
        $this->endtime = $endtime;

        return $this;
    }

    public function getDayofweek(): ?string
    {
        return $this->dayofweek;
    }

    public function setDayofweek(string $dayofweek): static
    {
        $this->dayofweek = $dayofweek;

        return $this;
    }


}
