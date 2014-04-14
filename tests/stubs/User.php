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
     * @column name
     * @var string
     */
    protected $name;

    /**
     * @column password
     * @var string
     */
    protected $password;

    /**
     * @column email
     * @var \Email
     */
    protected $email;

    /**
     * A cheap test to ensure
     * setter used when attributes added
     * via reflection
     * (Or should we always assume constructor arguments??)
     * @var bool
     */
    public $setEmailViaSetter = false;

    public function __construct($name, $password, Email $email, $id=null)
    {
        $this->name = $name;
        $this->password = $password;
        $this->setEmail($email);
        $this->id = $id;
    }

    /**
     * Set user email address
     * @param $email
     */
    public function setEmail($email)
    {
        $this->setEmailViaSetter = true;
        $this->email = $email;
    }
} 