<?php

class App {

    const _MAX_QUESTIONS = 5;

    public $log;
    public $user;
    private $multiplier = _MULTIPLIER;
    private $questionLog = [];
    private $currentQuestion = '';

    public function __construct()
    {
        $this->log = new Log;
        $this->user = new User;

        // $this->log->destroy(6);

        if (isset($_POST['answer'])) {
            $this->submitAnswer();
        }
    }

    public function currentQuestionNumber(): int
    {
        if (!empty($this->questionCount)) {
            // hack cache
            return $this->questionCount;
        }

        $count = 0;
        $today = date("Y-m-d");

        foreach ($this->getResults() as $date => $items) {

            if ($date != $today) {
                continue;
            }

            foreach ($items as $item) {

                ++$count;

                list($first, $second) = explode('x', $item[1]);
                $this->questionLog[] = (int) trim($second);
            }
        }

        $this->questionCount = $count;
        return $this->questionCount;
    }

    public function maxQuestions(): int
    {
        return self::_MAX_QUESTIONS;
    }

    private function getUniqueQuestionNumber()
    {
        $number = rand(0,10);

        if (in_array($number, $this->questionLog)) {
            return $this->getUniqueQuestionNumber();
        }

        $this->questionLog[] = $number;

        return $number;
    }

    public function getQuestion()
    {
        if (!empty($this->currentQuestion)) {
            return $this->currentQuestion;
        }

        if ($this->currentQuestionNumber() > 4) {
            $this->currentQuestion = null;
            return $this->currentQuestion;
        }

        $this->currentQuestion = $this->multiplier . ' x ' . $this->getUniqueQuestionNumber();
        return $this->currentQuestion;
    }
    
    public function submitAnswer(): bool
    {
        $question = (string) $_POST['question'];
        $answer = (int) $_POST['answer'];
        $question = explode('x', $question);

        // quasi validation
        $firstNumber = (int) trim(@$question[0]);
        $secondNumber = (int) trim(@$question[1]);

        if (empty($firstNumber) && $secondNumber >= 0) {
            return false;
        }

        if ($firstNumber != $this->multiplier) {
            return false;
        }
        
        $correctAnswer = $this->multiplier * $secondNumber;
        $date = date("Y-m-d");
        $this->previousQuestionStatus = ($answer == $correctAnswer);

        $this->log->create(
            [
                'date' => $date
                , 'question' => "{$this->multiplier} x {$secondNumber}"
                , 'actual_answer' => $answer
                , 'your_answer' => $correctAnswer
                , 'status' => $this->previousQuestionStatus
            ]
        );

        return $this->previousQuestionStatus;
    }

    public function getPreviousQuestionStatus()
    {
        if (!isset($this->previousQuestionStatus)) {
            return null;
        }

        return $this->previousQuestionStatus;
    }

    public function getResults(): array
    {
        $results = $this->log->getAll();

        $response = [];

        foreach ($results as $result) {
            $date = $result[0];
            if (!isset($response[$date])) {
                $response[$date] = [];
            }

            unset($result[0]);
            $response[$date][] = $result;
        }

        return $response;
    }
}