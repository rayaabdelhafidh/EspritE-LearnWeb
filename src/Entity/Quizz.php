<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizzRepository;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizzRepository")
 */

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
     /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $quizId=null;
   
       /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     
      
     */
   #[ORM\Column(length:255)]
   #[Assert\NotBlank(message:"description est requis.")]
   #[Assert\Length(max:255, maxMessage:"La description ne peut pas dépasser {{ limit }} caractères.")]
   private ?string $description=null;
       /**
     * @var string
     *
     * @ORM\Column(name="matiere", type="string", length=255, nullable=false)
     */
   #[ORM\Column(length:255)]
   #[Assert\NotBlank(message:"matier est requis.")]
   private ?string $matiere=null;

   #[ORM\OneToMany(mappedBy: 'quizz', targetEntity: Question::class)]
   private Collection $questions;

   public function __construct()
   {
       $this->questions = new ArrayCollection();
   }




    public function getQuizId(): ?int
    {
        return $this->quizId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }
    /** 
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

        return $this;
    }
    

}
