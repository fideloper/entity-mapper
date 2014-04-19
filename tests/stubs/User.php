<?php

/**
 * @table users
 * @repository \UserRepository
 */
class User {

    /**
     * @id
     * @column id
     * @var integer
     */
    protected $id;

    /**
     * @column username
     * @var string
     */
    protected $name;

    /**
     * @column email
     * @var \Email
     */
    protected $email;

    /**
     * @column votes
     * @var \Votes
     */
    protected $votes;

    /**
     * @relation hasOne \Post
     * @var \EntityMapper\Collection
     */
    protected $posts;

    public function __construct($name, Email $email, $votes=null, $id=null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->setVotes($votes);
        $this->id($id);
    }

    /**
     * Get Votes
     * @return \Votes
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * This is a setter with some logic
     * @setter votes
     * @param $votes
     */
    public function setVotes($votes)
    {
        if( ! $votes instanceof Votes )
        {
            $votes = new Votes($votes);
        }

        $this->votes = $votes;
    }

    /**
     * This is both a getter and setter
     * @getter id
     * @setter id
     * @param null $id
     * @return null
     */
    public function id($id=null)
    {
        if( is_null($id) )
        {
            return $this->id;
        }

        $this->id = $id;
    }

    /**
     * Get Email
     * @return \Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     * @param Email $email
     */
    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


} 