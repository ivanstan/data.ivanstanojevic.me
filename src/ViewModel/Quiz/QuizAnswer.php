<?php

namespace App\ViewModel\Quiz;

use App\Entity\QuizQuestionAnswer;

class QuizAnswer
{
    /** @var int */
    private $id;

    /** @var string */
    private $content;

    /** @var bool */
    private $correct;

    /** @var bool */
    private $selected;

    public function __construct(QuizQuestionAnswer $answer)
    {
        $this->id = $answer->getAnswer()->getId();
        $this->content = $answer->getAnswer()->getContent();
        $this->correct = $answer->getCorrect();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function isSelected(): bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): void
    {
        $this->selected = $selected;
    }
}
