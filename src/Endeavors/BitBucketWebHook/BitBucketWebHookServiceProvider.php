<?php namespace Endeavors\BitBucketWebHook;

use Illuminate\Support\ServiceProvider;
use Endeavors\BitBucketWebHook\Http\PushRequest;
use Endeavors\BitBucketWebHook\Http\PullRequest;
use Endeavors\BitBucketWebHook\Git\Repository as PHPGit;
use Illuminate\Http\Response;

class BitBucketWebHookServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
            __DIR__.'/../../config/config.php' => config_path('bitbucketwebhook.php'),
        ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register()
	{
        $app = $this->app;
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'bitbucketwebhook'
        );

        $app['web_hook.options'] = config('bitbucketwebhook.options');

        $app['web_hook.options.defaultRoutePrefix'] = $app['web_hook.options']['defaultRoutePrefix'];

        $app['web_hook.options.defaultRoutePattern'] = $app['web_hook.options']['defaultRoutePattern'];

        $app['web_hook.branch'] = config('bitbucketwebhook.branch');

        $app['web_hook.key'] = config('bitbucketwebhook.key');

        $app['web_hook.gitrepo'] = config('bitbucketwebhook.path_to_git_repo');

        $app['web_hook.remote_alias'] = config('bitbucketwebhook.remote_alias');
        
        // Define push request
        $app['web_hook.pushrequest'] = $app->share(function ($app) {
            return PushRequest::createFromGlobals();
        });

        $app['web_hook.pushrequest.changes.old'] = $app->share(function ($app) {
            return $app['web_hook.pushrequest']->getOldChanges();
        });

        $app['web_hook.pushrequest.changes.new'] = $app->share(function ($app) {
            return $app['web_hook.pushrequest']->getNewChanges();
        });

        $app['web_hook.pushrequest.changes.old.branch'] = $app->share(function ($app) {
            return $app['web_hook.pushrequest']->getOldChangesBranchName();
        });

        $app['web_hook.pushrequest.changes.new.branch'] = $app->share(function ($app) {
            return $app['web_hook.pushrequest']->getNewChangesBranchName();
        });
        
        // Define pull request
        $app['web_hook.pullrequest'] = $app->share(function ($app) {
            return PullRequest::createFromGlobals();
        });

        $app['web_hook.pullrequest.destination'] = $app->share(function ($app) {
            return $app['web_hook.pullrequest']->getDestination();
        });

        $app['web_hook.pullrequest.source'] = $app->share(function ($app) {
            return $app['web_hook.pullrequest']->getSource();
        });

        $app['web_hook.pushrequest.destination.branch'] = $app->share(function ($app) {
            return $app['web_hook.pullrequest']->getDestinationBranchName();
        });

        $app['web_hook.pushrequest.source.branch'] = $app->share(function ($app) {
            return $app['web_hook.pullrequest']->getSourceBranchName();
        });

        $that = $this;

        $this->app->bind("bitbucketwebhook.generate.key", function() use($that)
        {
            return new Commands\SecretKeyGenerateCommand( new \Illuminate\Filesystem\Filesystem, __DIR__.'/../../' , $that->app['config']->get('bit-bucket-web-hook::config')  );
        });


        $this->commands("bitbucketwebhook.generate.key");
        
        // @todo possibly move this to a controller?
        $app['router']->any($app['web_hook.options.defaultRoutePrefix'] . $app['web_hook.options.defaultRoutePattern'] . '/{key}', function($key) use($that) {

            if( $that->shouldNotContinueWithPush() && $that->shouldNotContinueWithPull() ) {
               // @todo return a proper response here
               echo 'cannot perform any git operations at this time' . "\r\n<br>";
               return;
            }
            if( $that->app['web_hook.key'] !== $key ) {
                echo 'webhook url is incorrect.'. "\r\n<br>";
                return;
            }

            // deploy here
            try
            {
	            $repo = new PHPGit($that->app['web_hook.gitrepo'], false, $that->app['web_hook.options']);
	            $message = $repo->pull($that->app['web_hook.remote_alias'] . ' ' . $that->app['web_hook.branch']);
                // return a proper response
                return Response::create($content = $message, $status = 200, $headers = array('Content-Type' => 'application/json'));
	        }
	        catch(\Exception $e)
	        {
	        	return Response::create($content = $e->getMessage(), $status = 200, $headers = array('Content-Type' => 'text/html'));
	        }
        });        
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('web_hook.pullrequest', 'web_hook.pushrequest', "bitbucketwebhook.generate.key");
	}

	public function shouldContinueWithPush()
	{
		// the branch name in the config should match the requested branch name from bitbucket
		// at this point im assuming the new and old branch names are the same, not 100% if this will be true all the time
		return ( $this->app['web_hook.branch'] === $this->app['web_hook.pushrequest.changes.new.branch'] ) && ( $this->app['web_hook.pushrequest.changes.new.branch'] === $this->app['web_hook.pushrequest.changes.old.branch'] );
	}

	public function shouldNotContinueWithPush()
	{
		return ! $this->shouldContinueWithPush();
	}

	public function shouldContinueWithPull()
	{
		// the branch name in the config should match the requested branch name from bitbucket
		// at this point im assuming the new and old branch names are the same, not 100% if this will be true all the time
		return ( $this->app['web_hook.branch'] === $this->app['web_hook.pushrequest.destination.branch'] ) && ( $this->app['web_hook.pushrequest.destination.branch'] !== $this->app['web_hook.pushrequest.source.branch'] ) && ( $this->app['web_hook.branch'] !== $this->app['web_hook.pushrequest.source.branch'] );
	}

    /**
     * Determine whether or not to satisfy the webhook pullrequest
     * If this is true, then we probably have a push request
     */
	public function shouldNotContinueWithPull()
	{
		return ! $this->shouldContinueWithPull();
	}

}
