<?php

class Log extends DB {

    public function __construct()
    {
        parent::__construct('log', $this->structure());
    }

    public function structure()
    {
        return [
            'date' => ''
            , 'question' => ''
            , 'actual_answer' => ''
            , 'your_answer' => ''
            , 'status' => 0
        ];
    }
}