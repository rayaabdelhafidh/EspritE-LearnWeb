<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantPresence
 *
 * @ORM\Table(name="etudiant_presence", indexes={@ORM\Index(name="id_presence", columns={"id_presence"})})
 * @ORM\Entity
 */
class EtudiantPresence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_etudiant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idEtudiant;

    /**
     * @var int
     *
     * @ORM\Column(name="id_presence", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idPresence;

    public function getIdEtudiant(): ?int
    {
        return $this->idEtudiant;
    }

    public function getIdPresence(): ?int
    {
        return $this->idPresence;
    }


}
