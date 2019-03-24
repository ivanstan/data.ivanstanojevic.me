<?php

namespace App\Entity\Quiz;

use App\Entity\Field\IdField;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class QuizResult
{
    use IdField;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var QuizQuestionResult[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\QuizQuestionResult", mappedBy="result")
     */
    private $questionResults;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", cascade={"persist", "remove"})
     */
    private $quiz;

    public function __construct()
    {
        $this->questionResults = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return QuizQuestionResult[]
     */
    public function value(): array
    {
        return $this->questionResults;
    }

    /**
     * @return QuizQuestionResult[]|Collection
     */
    public function getQuestionResults()
    {
        return $this->questionResults;
    }

    public function addQuestionResult(QuizQuestionResult $questionResult): self
    {
        if (!$this->questionResults->contains($questionResult)) {
            $this->questionResults[] = $questionResult;
            $questionResult->setResult($this);
        }

        return $this;
    }

    public function removeQuestionResult(QuizQuestionResult $questionResult): self
    {
        if ($this->questionResults->contains($questionResult)) {
            $this->questionResults->removeElement($questionResult);
            // set the owning side to null (unless already changed)
            if ($questionResult->getResult() === $this) {
                $questionResult->setResult(null);
            }
        }

        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }
}
