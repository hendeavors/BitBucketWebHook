<?php

namespace Endeavors\BitBucketWebHook\Tests;

use PHPUnit\Framework\TestCase;
use Endeavors\BitBucketWebHook\Git\Repository;

class RepositoryTest extends TestCase
{
    /**
     * @test
     **/
    public function repositoryCreation()
    {
        $repo = new Repository("/path/to/dir");
    }

    /** @test **/
    public function repositoryStatus()
    {
        $repo = (new Repository("C:\Users\adamr\adam_jte"))
        ->usingDebug()
        ->customizeOptions(['git_executable' => "C:\Program Files\Git\cmd\git.exe"]);
        dd($repo->status());
    }
}
