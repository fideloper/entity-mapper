<?php

class Votes implements EntityMapper\ValueObjectInterface {

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

    public function __toDb()
    {
        return $this->__toString();
    }
}