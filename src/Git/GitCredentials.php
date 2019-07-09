<?php

namespace Endeavors\BitBucketWebHook\Git;

class GitCredentials
{
    private $username;

    private $password;

    public function __construct(Username $username, Password $password)
    {
        $this->username = $username;

        $this->password = $password;
    }

    /**
     * Type safe factory creation
     * @param  Username $username The Username instance
     * @param  Password $password The Password instance
     * @return \Endeavors\BitBucketWebHook\Git\GitCredentials
     */
    public static function create(Username $username, Password $password)
    {
        return new static($username, $password);
    }

    /**
     * Scalar factory creation. Possible to swap values by mistake.
     * @param  string $username The raw value of $username
     * @param  string $password The raw value of $password
     * @return \Endeavors\BitBucketWebHook\Git\GitCredentials
     */
    public static function make(string $username, string $password)
    {
        return static::create(Username::create($username), Password::create($password));
    }

    public function __toString()
    {
        return sprintf("%s:%s", $this->username, $this->password);
    }
}
