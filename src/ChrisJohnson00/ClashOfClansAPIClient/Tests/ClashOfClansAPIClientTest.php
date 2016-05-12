<?php

/**
 * Created by PhpStorm.
 * User: cjohnson
 * Date: 5/11/16
 * Time: 8:06 PM
 */

use ChrisJohnson00\ClashOfClansAPIClient\Client\ClashOfClansAPIClient;

class ClashOfClansAPIClientTest extends PHPUnit_Framework_TestCase
{
    private $apiKey;
    /**
     * @var $apiClient ClashOfClansAPIClient
     */
    private $apiClient;
    private $buzzMock;

    public function setUp()
    {
        $testKeyLocation = __DIR__ . '/../../../../testKey.txt';
        if (!file_exists($testKeyLocation))
        {
            throw new RuntimeException($testKeyLocation . " was not found, please create the file in the same location as composer.json");
            die();
        }
        $this->apiKey    = file_get_contents($testKeyLocation);
        $this->apiClient = new ClashOfClansAPIClient($this->apiKey);
        $this->buzzMock  = $this->getMockBuilder('Buzz\Browser')->setMethods(array('get'))->getMock();
    }

    public function testBuildRequestHeaders()
    {
        $headers  = $this->apiClient->buildRequestHeaders();
        $expected = array('Accept' => 'application/json', 'authorization' => 'Bearer ' . $this->apiKey);
        $this->assertSame($expected, $headers);
    }

    public function testGetLocations()
    {
        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue(new stdClass()));
        $this->apiClient->setBuzzClient($this->buzzMock);
        $this->apiClient->getLocations();
        $url = "https://api.clashofclans.com/v1/locations";
        $this->assertSame($url, $this->apiClient->getUrl());
    }
}
