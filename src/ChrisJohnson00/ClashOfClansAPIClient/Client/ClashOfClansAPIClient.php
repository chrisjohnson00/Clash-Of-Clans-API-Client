<?php
/**
 * Created by PhpStorm.
 * User: cjohnson
 * Date: 5/11/16
 * Time: 7:48 PM
 */

namespace ChrisJohnson00\ClashOfClansAPIClient\Client;


use Buzz\Browser;

class ClashOfClansAPIClient
{
    private $apiHost;
    private $apiVersion;
    private $apiToken;
    private $url;

    /**
     * @var $buzzClient Browser
     */
    private $buzzClient;

    public function __construct($apiToken)
    {
        $this->setApiHost("https://api.clashofclans.com/");
        $this->setApiVersion("v1");
        $this->setApiToken($apiToken);
        $this->setBuzzClient(new Browser());
    }

    public function getLocations()
    {
        $this->url = $this->getApiHost() . $this->getApiVersion() . "/locations";

        return $this->sendRequest();
    }

    /**
     * @param Browser $buzzClient
     */
    public function setBuzzClient($buzzClient)
    {
        $this->buzzClient = $buzzClient;
    }

    /**
     * @return Browser
     */
    public function getBuzzClient()
    {
        return $this->buzzClient;
    }

    public function buildRequestHeaders()
    {
        return array('Accept' => 'application/json', 'authorization' => 'Bearer ' . $this->getApiToken());
    }


    private function sendRequest()
    {
        $buzz = $this->getBuzzClient();

        return $buzz->get($this->getUrl(), $this->buildRequestHeaders());
    }

    /**
     * @return mixed
     */
    public function getApiHost()
    {
        return $this->apiHost;
    }

    /**
     * @param mixed $apiHost
     */
    public function setApiHost($apiHost)
    {
        $this->apiHost = $apiHost;
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param mixed $apiVersion
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

}