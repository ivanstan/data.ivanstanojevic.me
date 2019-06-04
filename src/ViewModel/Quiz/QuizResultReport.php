<?php

namespace App\ViewModel\Quiz;

class QuizResultReport
{
    /** @var QuizQuestion[] */
    private $questions;

    /** @var int */
    private $score;

    /**
     * @return QuizQuestion[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function addQuestion($questions): void
    {
        $this->questions[] = $questions;
    }

    public function getTotal(): int
    {
        return count($this->questions);
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }
}
