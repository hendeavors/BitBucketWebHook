<?php

namespace Endeavors\BitBucketWebHook\Git;

class Username
{
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function create(string $username)
    {
        return new static($username);
    }

    public function __toString()
    {
        return sprintf("%s", $this->username);
    }
}
