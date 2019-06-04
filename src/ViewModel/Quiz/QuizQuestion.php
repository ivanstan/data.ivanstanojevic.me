<?php

namespace App\ViewModel\Quiz;

class QuizQuestion
{
    /** @var int */
    private $id;

    /** @var string */
    private $type;

    /** @var string */
    private $content;

    /** @var QuizAnswer[] */
    private $answers;

    /** @var bool */
    private $correct = false;

    public function __construct(\App\Entity\QuizQuestion $question)
    {
        $this->id = $question->getId();
        $this->content = $question->getContent();
        $this->type = $question->getType();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return QuizAnswer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function addAnswer(QuizAnswer $answer): void
    {
        $this->answers[] = $answer;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }
}
