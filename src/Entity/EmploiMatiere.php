<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmploiMatiereRepository;
use Symfony\Component\Validator\Constraints as Assert;





 #[ORM\Entity(repositoryClass: EmploiMatiereRepository::class)]
      class EmploiMatiere
      {
      
      
         
          #[ORM\Id]
#[ORM\ManyToOne(targetEntity: "Emploi")]
#[ORM\JoinColumn(name: "emploiId", referencedColumnName: "emploiId", nullable: false)]

          private Emploi $emploi;
      
        
          #[ORM\Id]
          #[ORM\ManyToOne(targetEntity: Matiere::class)]
          #[ORM\JoinColumn(name: 'matiereId', referencedColumnName: 'idm', nullable: false)]

          private ?Matiere $matiere = null;



      #[ORM\Column(name: "startTime", type: "time", nullable: false)]
      #[Assert\NotBlank(message:"temps de début de la séance est requis.")]
      private ?\DateTimeInterface $starttime;


      #[ORM\Column(name: "endTime", type: "time", nullable: false)]
      #[Assert\NotBlank(message:"temps de la fin de la séance est requis.")]
      private ?\DateTimeInterface $endtime;
      
      
    
          #[ORM\Column(name: "dayOfWeek", type: "string", length: 20, nullable: false)]
          #[Assert\NotBlank(message:"le jour de la semaine est requis.")]
          private $dayofweek;
      
         public function getEmploi(): Emploi
          {
              return $this->emploi;
          }
      
          public function getMatiere(): ?Matiere
    {
        return $this->matiere;
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
   
          public function setEmploi(?Emploi $emploi): static
          {
              $this->emploi = $emploi;
   
              return $this;
          }

          public function setMatiere(?Matiere $matiere): static
          {
              $this->matiere = $matiere;

              return $this;
          }
      
      
      
      }
