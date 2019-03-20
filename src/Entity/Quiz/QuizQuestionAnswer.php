<?php

namespace App\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 */
class QuizQuestionAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $correct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\QuizQuestion", inversedBy="answers")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\QuizAnswer", inversedBy="answers")
     * @Groups({"api_course_instance"})
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getQuestion(): ?QuizQuestion
    {
        return $this->question;
    }

    public function setQuestion(?QuizQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?QuizAnswer
    {
        return $this->answer;
    }

    public function setAnswer(?QuizAnswer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
