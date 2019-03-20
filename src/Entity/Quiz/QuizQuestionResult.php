<?php

namespace App\Entity\Quiz;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class QuizQuestionResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var QuizResult
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\QuizResult", inversedBy="questionResults", cascade={"persist", "remove"})
     */
    private $result;

    /**
     * @var QuizQuestion
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\QuizQuestion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @var QuizAnswer
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\QuizAnswer")
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResult(): QuizResult
    {
        return $this->result;
    }

    public function setResult(QuizResult $result): void
    {
        $this->result = $result;
    }

    /**
     * @return QuizAnswer[]
     */
    public function getCorrect(): array
    {
        return $this->question->getCorrectAnswers();
    }
}
