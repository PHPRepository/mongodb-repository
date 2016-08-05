<?php

namespace NilPortugues\Example;

use DateTime;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Identity;

/**
 * Class User.
 */
class User implements Identity
{
    protected $userId;
    protected $username;
    protected $alias;
    protected $email;
    protected $registeredOn;

    /**
     * User constructor.
     *
     * @param          $userId
     * @param          $username
     * @param          $alias
     * @param          $email
     * @param DateTime $registeredOn
     */
    public function __construct($userId, $username, $alias, $email, DateTime $registeredOn)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->alias = $alias;
        $this->email = $email;
        $this->registeredOn = $registeredOn;
    }

    /**
     * Returns value for `userId`.
     *
     * @return mixed
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * Returns value for `username`.
     *
     * @return mixed
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Returns value for `alias`.
     *
     * @return mixed
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * Returns value for `email`.
     *
     * @return mixed
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Returns value for `registeredOn`.
     *
     * @return DateTime
     */
    public function registeredOn()
    {
        return $this->registeredOn;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id();
    }
}
