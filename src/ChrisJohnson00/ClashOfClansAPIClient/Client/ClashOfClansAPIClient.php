<?php
/**
 * Created by PhpStorm.
 * User: cjohnson
 * Date: 5/11/16
 * Time: 7:48 PM
 */

namespace ChrisJohnson00\ClashOfClansAPIClient\Client;


use Buzz\Browser;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;

class ClashOfClansAPIClient
{
    private $apiHost;
    private $apiVersion;
    private $apiToken;
    private $url;

    /**
     * @var $jms SerializerBuilder
     */
    private $jms;

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
        $this->setJms(SerializerBuilder::create()->build());
        AnnotationRegistry::registerLoader('class_exists'); //so jms annotations load automatically
    }

    public function getLocations()
    {
        $this->url = $this->getApiHost() . $this->getApiVersion() . "/locations";

        $message = $this->sendRequest();

        return $this->jms->deserialize($message->getContent(), 'ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations', 'json');
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

    /**
     * @return SerializerBuilder
     */
    public function getJms()
    {
        return $this->jms;
    }

    /**
     * @param SerializerBuilder $jms
     */
    public function setJms($jms)
    {
        $this->jms = $jms;
    }

}