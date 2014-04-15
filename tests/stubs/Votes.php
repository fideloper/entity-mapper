<?php

class Votes {

    private $votes;

    public function __construct($votes)
    {
        $this->setVotes($votes);
    }

    private function setVotes($votes)
    {
        if( ! is_numeric($votes) )
        {
            throw new \InvalidArgumentException('Votes must be a number');
        }

        $this->votes = $votes;
    }

    public function getVotes()
    {
        return $this->votes;
    }

    public function __toString()
    {
        return $this->getVotes();
    }
} 