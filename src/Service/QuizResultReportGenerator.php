<?php

namespace App\Service;

use App\Entity\Quiz\QuizAnswer;
use App\Entity\Quiz\QuizResult;

class QuizResultReportGenerator
{
    public function getReport(QuizResult $result): array
    {
        $questions = [];
        $score = 0;

        foreach ($result->getQuestionResults() as $questionResult) {
            $question = [];
            $question['content'] = $questionResult->getQuestion()->getContent();
            $question['answers'] = [];

            $correctAnswerIds = [];
            /** @var QuizAnswer $correctAnswer */
            foreach ($questionResult->getQuestion()->getCorrectAnswers() as $correctAnswer) {
                $correctAnswerIds[] = $correctAnswer->getId();
            }

            $userAnswerIds = [];
            /** @var QuizAnswer $correctAnswer */
            foreach ($questionResult->getAnswer()->getAnswers() as $userAnswer) {
                $userAnswerIds[] = $userAnswer->getAnswer()->getId();
            }

            $answers = [];
            foreach ($questionResult->getQuestion()->getAnswers() as $answer) {
                $correct = in_array($answer->getAnswer()->getId(), $correctAnswerIds, true);
                $selected =  in_array($answer->getAnswer()->getId(), $userAnswerIds, true);

                if ($correct && $selected) {
                    $score++;
                }

                $question['answers'][] = [
                    'content' => $answer->getAnswer()->getContent(),
                    'correct' => $correct,
                    'selected' => $selected,
                ];
            }

            $questions[] = $question;
        }

        return [
            'score' => [
                'total' => count($questions),
                'correct' => $score,
            ],
            'result' => $questions,
        ];
    }
}
