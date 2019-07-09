<?php

namespace Endeavors\BitBucketWebHook\Git;

class Password
{
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public static function create(string $password)
    {
        return new static($password);
    }

    public function __toString()
    {
        return sprintf("%s", $this->password);
    }
}
