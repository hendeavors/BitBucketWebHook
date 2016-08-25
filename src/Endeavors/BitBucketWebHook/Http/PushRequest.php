<?php namespace Endeavors\BitBucketWebHook\Http;

use Illuminate\Http\Request as LaravelRequest;

class PushRequest extends LaravelRequest {

    /**
     * The request body from bitbucket is json. We'll
     * Use LaravalRequest to get our json input properly formatted
     * Request::getPush will get our push event request
     * @return array
     */
    public function getPush()
    {
    	return $this->json('push');
    }
    
    /**
     * Here we will get our old changes from the push event
     * Bitbucket sends old and new nodes in the request body
     * @return array
     */
    public function getOldChanges()
    {
    	$result = [];

    	if( null !== $this->getPush() )
    	{
            $result = reset($this->getPush()['changes'])['old'];
        }

        return $result;
    }
    
    /**
     * We'll need to know the branch name to perform the operation on
     * 
     * @return string
     */
    public function getOldChangesBranchName()
    {
    	$result = null;

    	if( null !== $this->getPush() )
    	{
    	    $result = $this->getOldChanges()['name'];
        }

        return $result;
    }
    
    /**
     * Here we will get our new changes from the push event
     * Bitbucket sends old and new nodes in the request body
     * @return array
     */
    public function getNewChanges()
    {
    	$result = [];

    	if( null !== $this->getPush() )
    	{
            $result = reset($this->getPush()['changes'])['new'];
        }
        
        return $result;
    }

    /**
     * We'll need to know the branch name to perform the operation on
     * 
     * @return string
     */
    public function getNewChangesBranchName()
    {
    	$result = null;

    	if( null !== $this->getPush() )
    	{
    	    $result = $this->getNewChanges()['name'];
    	}

    	return $result;
    }
    
    /**
     * Get the raw request body
     * Bitbucket sends json so this should be json
     * @return mixed
     */
    public function getRaw()
    {
    	return $this->getContent();
    }
}

// BitBucketWebHook::deploy()