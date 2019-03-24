<?php

namespace App\Controller;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\QuizAnswer;
use App\Entity\Quiz\QuizQuestion;
use App\Entity\Quiz\QuizQuestionResult;
use App\Entity\Quiz\QuizResult;
use App\Service\QuizResultReportGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz/{id}", name="app_quiz")
     */
    public function quiz(int $id, Request $request, QuizResultReportGenerator $generator): Response
    {
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->getQuiz($id);

        if ($request->isMethod('post')) {
            $em = $this->getDoctrine()->getManager();

            $result = new QuizResult();
            $result->setQuiz($quiz);
            $result->setEmail($request->request->get('email'));
            foreach ($request->request->get('question') as $questionId => $answerId) {
                $question = $em->getRepository(QuizQuestion::class)->find($questionId);
                $answer = $em->getRepository(QuizAnswer::class)->find($answerId);

                $questionResult = new QuizQuestionResult();
                $questionResult->setQuestion($question);
                $questionResult->setAnswer($answer);
                $questionResult->setResult($result);
                $result->addQuestionResult($questionResult);
                $em->persist($questionResult);
            }

            $em->persist($result);
            $em->flush();

            $report = $generator->getReport($result);

            return $this->render('pages/quiz/result.html.twig', [
                'report' => $report,
//                'quiz' => $quiz,
//                'total' => $total,
//                'correct' => $correctNumber,
            ]);
        }

        return $this->render('pages/quiz/quiz.html.twig', [
            'quiz' => $quiz,
        ]);
    }
}
