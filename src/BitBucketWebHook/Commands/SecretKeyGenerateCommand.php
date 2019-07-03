<?php namespace Endeavors\BitBucketWebHook\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SecretKeyGenerateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bitbucketwebhook:generate.key';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Set the webhook key";

	/**
	 * Create a new key generator command.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @return void
	 */
	public function __construct(Filesystem $files, $packagePath, $config)
	{
		parent::__construct();

		$this->files = $files;

		$this->packagePath = $packagePath;

		$this->config = $config;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		list($path, $contents) = $this->getKeyFile();

		$key = $this->getRandomKey();

		$contents = str_replace($this->config['key'], $key, $contents);

		$this->files->put($path, $contents);

		$this->config['key'] = $key;

		$this->info("BitBucketWebHook key [$key] set successfully.");
	}

	/**
	 * Get the key file and contents.
	 *
	 * @return array
	 */
	protected function getKeyFile()
	{
		$env = $this->option('env') ? $this->option('env').'/' : '';

		$contents = $this->files->get($path = $this->packagePath . '/config/config.php');

		return array($path, $contents);
	}

	/**
	 * Generate a random key for the application.
	 *
	 * @return string
	 */
	protected function getRandomKey()
	{
		return Str::random(32);
	}

}
