<?php

namespace App\Repository;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getQuiz($id)
    {
        return $this->createQueryBuilder('quiz', 'quiz.id')
            ->select('quiz', 'questions', 'answers', 'answer')
            ->leftJoin('quiz.questions', 'questions')
            ->leftJoin('questions.answers', 'answers')
            ->leftJoin('answers.answer', 'answer')
            ->andWhere('quiz = :quiz')->setParameter('quiz', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
