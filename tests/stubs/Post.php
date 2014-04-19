<?php

/**
 * @table posts
 */
class Post {

    /**
     * @id
     * @column id
     * @var integer
     */
    protected $id;

    /**
     * @column title
     * @var string
     */
    protected $title;

    /**
     * @column body
     * @var string
     */
    protected $body;

    /**
     * @column user_id
     * @relation belongsTo \User
     * @var \User
     */
    protected $user;

    public function __construct($title, $body, User $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
    }

    /**
     * @getter id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @getter title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @getter user
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
} 