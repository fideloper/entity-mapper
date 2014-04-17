<?php

class Email {

    protected $email;

    public function __construct($email)
    {
        $this->setEmail($email);
    }

    protected function setEmail($email)
    {
        if( ! filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
            throw new \InvalidArgumentException('Valid email required');
        }

        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

} 