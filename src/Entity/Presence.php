<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Presence
 *
 * @ORM\Table(name="presence", indexes={@ORM\Index(name="fk_Presence_Classe", columns={"idClasse"}), @ORM\Index(name="fk_Presence_NomClasse", columns={"nomClasse"})})
 * @ORM\Entity
 */
class Presence
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPresence", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpresence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="seance", type="string", length=250, nullable=false)
     */
    private $seance;

    /**
     * @var \Classe
     *
     * @ORM\ManyToOne(targetEntity="Classe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nomClasse", referencedColumnName="nomClasse")
     * })
     */
    private $nomclasse;

    /**
     * @var \Classe
     *
     * @ORM\ManyToOne(targetEntity="Classe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClasse", referencedColumnName="idClasse")
     * })
     */
    private $idclasse;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Classe", mappedBy="idpresence")
     */
    private $idclass = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idclass = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdpresence(): ?int
    {
        return $this->idpresence;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSeance(): ?string
    {
        return $this->seance;
    }

    public function setSeance(string $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getNomclasse(): ?Classe
    {
        return $this->nomclasse;
    }

    public function setNomclasse(?Classe $nomclasse): static
    {
        $this->nomclasse = $nomclasse;

        return $this;
    }

    public function getIdclasse(): ?Classe
    {
        return $this->idclasse;
    }

    public function setIdclasse(?Classe $idclasse): static
    {
        $this->idclasse = $idclasse;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getIdclass(): Collection
    {
        return $this->idclass;
    }

    public function addIdclass(Classe $idclass): static
    {
        if (!$this->idclass->contains($idclass)) {
            $this->idclass->add($idclass);
            $idclass->addIdpresence($this);
        }

        return $this;
    }

    public function removeIdclass(Classe $idclass): static
    {
        if ($this->idclass->removeElement($idclass)) {
            $idclass->removeIdpresence($this);
        }

        return $this;
    }

}
