<?php namespace Endeavors\BitBucketWebHook\Http;

use Illuminate\Http\Request as LaravelRequest;

class PullRequest extends LaravelRequest {

    /**
     * The request body from bitbucket is json. We'll
     * Use LaravalRequest to get our json input properly formatted
     * Request::getPull will get our pullrequest event request
     * @return array
     */
	public function getPull()
	{
		return $this->json('pullrequest');
	}
    
    /**
     * Get the pull request destination
     * @return array
     */
	public function getDestination()
	{
		$results = [];

        if( null !== $this->getPull() )
        {
        	$results = $this->getPull()['destination'];
        }

        return $results;
	}
    
    /**
     * Get the pull request source
     * @return array
     */
	public function getSource()
	{
        $results = [];

        if( null !== $this->getPull() )
        {
        	$results = $this->getPull()['source'];
        }

        return $results;
	}

    /**
     * We'll need to know the branch name to perform the operation on
     * 
     * @return string|null
     */
	public function getDestinationBranchName()
	{
        $result = null;

        if( null !== $this->getPull() )
        {
        	$result = $this->getDestination()['branch']['name'];
        }

        return $result;
	}

    /**
     * We'll need to know the branch name to perform the operation on
     * 
     * @return string|null
     */
	public function getSourceBranchName()
	{
        $result = null;

        if( null !== $this->getPull() )
        {
        	$result = $this->getSource()['branch']['name'];
        }

        return $result;
	}
}