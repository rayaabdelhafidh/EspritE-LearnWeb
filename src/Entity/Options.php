<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OptionsRepositor;
#[ORM\Entity(repositoryClass: OptionsRepository::class)]

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
   

    #[ORM\Column(length:255)]
    private ?string $optionContent=null;
   
    #[ORM\Column(length:255)]
    private ?bool  $isCorrect;

    #[ORM\ManyToOne(inversedBy: 'options')]
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
