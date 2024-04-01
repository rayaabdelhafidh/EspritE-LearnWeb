<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
#[ORM\Entity(repositoryClass:EvenementRepository::class)]
class Evenement
{
    /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idevenement=null;
/**
     * @var string
     *
     * @ORM\Column(name="nomevenement", type="string", length=155, nullable=false)
     */
    #[ORM\Column(length: 155)]
    private ?string $nomevenement=null;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateevenement", type="date", nullable=true)
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateevenement=null;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuevenement", type="string", length=255, nullable=false)
     */
    #[ORM\Column(length: 255)]
    private ?string $lieuevenement= null;

    /**
     * @var float
     *
     * @ORM\Column(name="PrixEvenement", type="float", precision=10, scale=0, nullable=false)
     */
    #[ORM\Column]
    private ?float $prixevenement= null;

    /**
     * @var string
     *
     * @ORM\Column(name="afficheevenement", type="string", length=255, nullable=false)
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "affiche cannot be blank")]
    private ?string $afficheevenement= null;

    /**
     * @var \Club
     *
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="club", referencedColumnName="idclub")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'evenement')]
    private ?Club $club= null;

    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Participant::class)]
    private Collection $evenement_participant;


    public function getIdevenement(): ?int
    {
        return $this->idevenement;
    }

    public function getNomevenement(): ?string
    {
        return $this->nomevenement;
    }

    public function setNomevenement(?string $nomevenement): static
    {
        $this->nomevenement = $nomevenement;

        return $this;
    }

    public function getDateevenement(): ?\DateTimeInterface
    {
        return $this->dateevenement;
    }

    public function setDateevenement(?\DateTimeInterface $dateevenement): static
    {
        $this->dateevenement = $dateevenement;

        return $this;
    }

    public function getLieuevenement(): ?string
    {
        return $this->lieuevenement;
    }

    public function setLieuevenement(?string $lieuevenement): static
    {
        $this->lieuevenement = $lieuevenement;

        return $this;
    }

    public function getPrixevenement(): ?float
    {
        return $this->prixevenement;
    }

    public function setPrixevenement(?float $prixevenement): static
    {
        $this->prixevenement = $prixevenement;

        return $this;
    }

    public function getAfficheevenement(): ?string
    {
        return $this->afficheevenement;
    }

    public function setAfficheevenement(?string $afficheevenement): static
    {
        $this->afficheevenement = $afficheevenement;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }


}