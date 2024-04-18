<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe", uniqueConstraints={@ORM\UniqueConstraint(name="nomClasse_unique", columns={"nomClasse"})})
 * @ORM\Entity
 */
class Classe
{
    /**
     * @var int
     *
     * @ORM\Column(name="idClasse", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idclasse;

    /**
     * @var string
     *
     * @ORM\Column(name="nomClasse", type="string", length=255, nullable=false)
     */
    private $nomclasse;

    /**
     * @var string
     *
     * @ORM\Column(name="filiere", type="string", length=0, nullable=false)
     */
    private $filiere;

    /**
     * @var int
     *
     * @ORM\Column(name="nbreEtudi", type="integer", nullable=false)
     */
    private $nbreetudi;

    /**
     * @var string
     *
     * @ORM\Column(name="niveaux", type="string", length=255, nullable=false)
     */
    private $niveaux;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Presence", inversedBy="idclass")
     * @ORM\JoinTable(name="presence_classe",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idclass", referencedColumnName="idClasse")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idPresence", referencedColumnName="idPresence")
     *   }
     * )
     */
    private $idpresence = array();

    /**
     * @ORM\OneToMany(targetEntity="Emploi", mappedBy="classe")
     */
    private $emplois;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idpresence = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getIdclasse(): ?int
    {
        return $this->idclasse;
    }

    public function getNomclasse(): ?string
    {
        return $this->nomclasse;
    }

    public function setNomclasse(string $nomclasse): static
    {
        $this->nomclasse = $nomclasse;

        return $this;
    }

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(string $filiere): static
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getNbreetudi(): ?int
    {
        return $this->nbreetudi;
    }

     /**
     * @return Collection<int, Emploi>
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }


    public function setNbreetudi(int $nbreetudi): static
    {
        $this->nbreetudi = $nbreetudi;

        return $this;
    }

    public function getNiveaux(): ?string
    {
        return $this->niveaux;
    }

    public function setNiveaux(string $niveaux): static
    {
        $this->niveaux = $niveaux;

        return $this;
    }

    /**
     * @return Collection<int, Presence>
     */
    public function getIdpresence(): Collection
    {
        return $this->idpresence;
    }

    public function addIdpresence(Presence $idpresence): static
    {
        if (!$this->idpresence->contains($idpresence)) {
            $this->idpresence->add($idpresence);
        }

        return $this;
    }

    public function removeIdpresence(Presence $idpresence): static
    {
        $this->idpresence->removeElement($idpresence);

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomclasse. ' - ' . $this->filiere;
    }

}
