<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
        /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idparticipant=null;

    #[ORM\Column(length: 255)]
    private ?string $token=null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datereservation=null;

 
    #[ORM\ManyToOne(inversedBy: 'evenement')]
        private ?Evenement $idevenement= null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?User $iduser= null;

    public function getIdparticipant(): ?int
    {
        return $this->idparticipant;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getDatereservation(): ?\DateTimeInterface
    {
        return $this->datereservation;
    }

    public function setDatereservation(\DateTimeInterface $datereservation): static
    {
        $this->datereservation = $datereservation;

        return $this;
    }

    public function getIdevenement(): ?Evenement
    {
        return $this->idevenement;
    }

    public function setIdevenement(?Evenement $idevenement): static
    {
        $this->idevenement = $idevenement;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }


}
