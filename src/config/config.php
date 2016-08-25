<?php

return array(

   /*
    |--------------------------------------------------------------------------
    | WebHookService Settings
    |--------------------------------------------------------------------------
    |
    | Set the branch you wish to be used by the webhook
    | The remote alias ( normally origin )
    | Set the path to your git repo on your hard drive ( where the git folder is located ).
    | Place the key ( secret ) at the end of your url defined on bitbucket
    */
    'branch' => 'your-branch-name',
    'remote_alias' => 'origin',
    'path_to_git_repo' => 'path/to/git/repo',
    'key' => 'ISCBLJUNd0tPN3COGlhvKkXwtk1dm47J',

    /*
     |--------------------------------------------------------------------------
     | Extra options
     |--------------------------------------------------------------------------
     |
     | Define your options:
     | Here you may set your route prefix and pattern the bitbucket webhook points to.
     | Set your credentials for your remote repository and the path on the filesystem to
     | The git executable ( include the exe ) e.g. C:\Program Files\Git\bin\git
     |
     */
    'options' => array(
        'useDefaultRoute'     => true,
        'defaultRoutePattern' => '/webhook',
        'defaultRoutePrefix'  => 'bitbucket/api',
        'login' => 'username',
        'password' => 'password',
        'repository' => 'your-remote-repository.git',
        'git_executable' => '"path\to\git\git"', // path of the executable on the server
    ),

);
