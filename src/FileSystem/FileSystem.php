<?php namespace Endeavors\BitBucketWebHook\FileSystem;

use Illuminate\Filesystem\Filesystem as BaseFileSystem;

abstract class FileSystem extends BaseFileSystem
{
	abstract public function all($directory, $recursive=false);

	//abstract public function get($path);

	//abstract public function delete($path);
}