<?php namespace Endeavors\BitBucketWebHook\FileSystem;

class Local extends FileSystem
{

	public function __construct($includedFileExtensions=[], $includedDirectories=[])
	{
		$this->includedFileExtensions = $includedFileExtensions;
		$this->includedDirectories = $includedDirectories;
	}
	public function all($directory, $recursive=false)
	{
		$files = [];

		foreach( $this->includedDirectories as $includedDirectory )
		{
			if( $recursive )
			{
				foreach($this->allFiles($directory.'/'.$includedDirectory) as $fileObject)
				{
					if( in_array($fileObject->getExtension(), $this->includedFileExtensions) )
					{
                        $files[]=[
                            'directory'    =>  $directory.'/'.$includedDirectory,
                            'relativePath' => $fileObject->getRelativePath(),
                            'path'         =>  $fileObject->getRelativePathname()
                        ];
					}
				}
	        }
	        else
	        {
	        	$files[] = $this->files($directory.'/'.$includedDirectory);
	        }
		}

		return $files;
	}
}