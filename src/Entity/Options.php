<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OptionsRepositor;
use App\Entity\Quizz;
#[ORM\Entity(repositoryClass: OptionsRepository::class)]
/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionsRepository")
 */

class Options
{
     /**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int  $optionId=null;
    
             /**
     * @var string
     *
     * @ORM\Column(name="option_content", type="string", length=255, nullable=false)
     */

    #[ORM\Column(length:255)]
    private ?string $optionContent=null;
       /**
     * @var bool|null
     *
     * @ORM\Column(name="is_correct", type="boolean", nullable=false)
     */
   
    #[ORM\Column(length:255)]
    private ?bool  $isCorrect;

   /**
    * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="options")
    * @ORM\JoinColumn(name="questionid", referencedColumnName="questionid", nullable=false)
   */
   #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'options')]
   #[ORM\JoinColumn(name: "questionid", referencedColumnName: "questionid", nullable: false)]
   
    private ?Question $question=null;



    public function getOptionId(): ?int
    {
        return $this->optionId;
    }

    public function getOptionContent(): ?string
    {
        return $this->optionContent;
    }

    public function setOptionContent(?string $optionContent): static
    {
        $this->optionContent = $optionContent;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(?bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }
    

}
