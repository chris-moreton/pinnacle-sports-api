<?php
namespace Netsensia\Pinnacle\Api\Client;

use Netsensia\Pinnacle\Api\Client\Common\Guzzle\Client as GuzzleClient;

use GuzzleHttp\Message\Response;


class Client
{
    /**
     * The base URI for the API
     */
    private $apiBaseUri = 'https://api.pinnacle.com/';
    
    /**
     * 
     * @var GuzzleClient
     */
    private $guzzleClient;
    
    /**
     * 
     * @var string
     */
    private $apiKey;
    
    /**
     * The status code from the last API call
     * 
     * @var number
     */
    private $lastStatusCode;
    
    /**
     * The content body from the last API call
     * 
     * @var string
     */
    private $lastContent;
    
    /**
     * Did the last API call return with an error?
     * 
     * @var boolean
     */
    private $isError;

    /**
     * Create an API client for the given uri endpoint.
     * 
     * @param string $apiKey  The API key
     */
    public function __construct(
        $apiKey = null
    )
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the GuzzleClient.
     *
     * @return GuzzleClient
     */
    private function client()
    {

        if( !isset($this->guzzleClient) ){
            $this->guzzleClient = new GuzzleClient();
        }

        $this->guzzleClient->setBasicAuth( $this->apiKey );

        return $this->guzzleClient;

    }

    /**
     * Generic function for GET calls
     * 
     * @param string $url
     * @return ßboolean|mixed
     */
    private function get($url)
    {
        $response = $this->client()->get($url);
        
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
    
    /**
    * Get Sports
    *
    * @return boolean|mixed
    */
    public function getSports()
    {
        return $this->get($this->apiBaseUri . 'v2/sports');
    }
    
    /**
     * Get Leagues
     *
     * @param number $sportId
     * 
     * @return boolean|mixed
     */
    public function getLeagues($sportId)
    {
        return $this->get($this->apiBaseUri . 'v2/leagues?sportId=' . $sportId);
    }
    
    /**
     * Get Fixtures
     *
     * @param number $sportId
     * @param array $leagueIds
     * 
     * @return boolean|mixed
     */
    public function getFixtures($sportId, $leagueIds = null, $since = null, $liveOnly = false)
    {
        $url = $this->apiBaseUri . 'v1/fixtures?sportId=' . $sportId;
        
        if ($leagueIds) {
            $url .= '&leagueIds=[' . implode(',', $leagueIds) . ']';
        }
        
        if ($since) {
            $url .= '&since=' . $since;
        }
        
        if ($liveOnly) {
            $url .= '&isLive=Y';
        }
        
        return $this->get($url);
    }
    
    /**
     * @return the $isError
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @param boolean $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return the $apiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return the $lastStatusCode
     */
    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
    }

    /**
     * @param number $lastStatusCode
     */
    public function setLastStatusCode($lastStatusCode)
    {
        $this->lastStatusCode = $lastStatusCode;
    }

    /**
     * @return the $lastContent
     */
    public function getLastContent()
    {
        return $this->lastContent;
    }

    /**
     * @param string $lastContent
     */
    public function setLastContent($lastContent)
    {
        $this->lastContent = $lastContent;
    }

    /**
     * Log the response of the API call and set some internal member vars
     * If content body is JSON, convert it to an array
     *
     * @param Response $response
     * @param bool $isSuccess
     * @return boolean
     *
     * @todo - External logging
     */
    public function log(Response $response, $isSuccess=true)
    {
        $this->lastStatusCode = $response->getStatusCode();
    
        $responseBody = (string)$response->getBody();
        $jsonDecoded = json_decode($responseBody, true);
    
        if (json_last_error() == JSON_ERROR_NONE) {
            $this->lastContent = $jsonDecoded;
        } else {
            $this->lastContent = $responseBody;
        }
    
        // @todo - Log properly
        if (!$isSuccess) {
        }
    
        $this->setIsError(!$isSuccess);
    
        return $isSuccess;
    }
    
}
