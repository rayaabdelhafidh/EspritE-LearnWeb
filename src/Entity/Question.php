<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\QuestionRepository;


/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */

#[ORM\Entity(repositoryClass: QuestionRepository::class)]

class Question
{
     /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int  $questionid=null;
          /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255, nullable=false)
     */

   #[ORM\Column(length:255)]
   #[Assert\NotBlank(message:"Le contenu ne peut pas être vide.")]
   private ?string $content=null;
   /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)

     */
   #[ORM\Column]
   #[Assert\Type(
    type: 'integer',
    message: "numéro de salle doit étre un numéro"
    )]
   private ?int $score=null;
   
/**
 * @ORM\ManyToOne(targetEntity=Quizz::class, inversedBy="questions")
 * @ORM\JoinColumn(name="quiz_id", referencedColumnName="quiz_id", nullable=false)
 */
                           
#[ORM\ManyToOne(targetEntity: Quizz::class, inversedBy: 'questions')]
#[ORM\JoinColumn(name: "quiz_id", referencedColumnName: "quiz_id", nullable: false)]
 private ?Quizz $quiz;




    /**
     * @ORM\OneToMany(mappedBy="question", targetEntity=Options::class)
     */
    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Options::class)]
    private Collection $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    

    public function getQuestionid(): ?int
    {
        return $this->questionid;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getQuiz(): ?Quizz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quizz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }
    /** 
     * @return Collection<int, Options>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Options $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setQuestion($this);
        }

        return $this;
    }

    public function removeOption(Options $option): static
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getQuestion() === $this) {
                $option->setQuestion(null);
            }
        }

        return $this;
    }


}
