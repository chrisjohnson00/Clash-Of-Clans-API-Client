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
    private $responseMock;
    private $jmsMock;

    public function setUp()
    {
        $testKeyLocation = __DIR__ . '/../../../../../testKey.txt';
        if (!file_exists($testKeyLocation))
        {
            throw new RuntimeException($testKeyLocation . " was not found, please create the file in the same location as composer.json");
            die();
        }
        $this->apiKey       = file_get_contents($testKeyLocation);
        $this->apiClient    = new ClashOfClansAPIClient($this->apiKey);
        $this->buzzMock     = $this->getMockBuilder('Buzz\Browser')->setMethods(array('get'))->getMock();
        $this->responseMock = $this->getMockBuilder('Buzz\Message')->setMethods(array('getContent'))->getMock();
        $this->jmsMock      = $this->getMockBuilder('JMS\Serializer')->setMethods(array('deserialize'))->getMock();
    }

    public function testBuildRequestHeaders()
    {
        $headers  = $this->apiClient->buildRequestHeaders();
        $expected = array('Accept' => 'application/json', 'authorization' => 'Bearer ' . $this->apiKey);
        $this->assertSame($expected, $headers);
    }

    public function testGetLocationsUrlBuilding()
    {
        $this->responseMock->expects($this->atLeastOnce())->method('getContent')->will($this->returnValue("string of stuff"));
        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue($this->responseMock));
        $this->jmsMock->expects($this->atLeastOnce())->method('deserialize')->will($this->returnValue(new stdClass()));
        $this->apiClient->setBuzzClient($this->buzzMock);
        $this->apiClient->setJms($this->jmsMock);
        $this->apiClient->getLocations();
        $url = "https://api.clashofclans.com/v1/locations";
        $this->assertSame($url, $this->apiClient->getUrl());
    }

    public function testGetLocationsDeserialization()
    {
        $json = '{
  "items": [
    {
      "id": 32000006,
      "name": "International",
      "isCountry": false
    },
    {
      "id": 32000007,
      "name": "Afghanistan",
      "isCountry": true,
      "countryCode": "AF"
    }
    ]}';

        $response = new \Buzz\Message\Response();
        $response->setContent($json);

        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue($response));
        $this->apiClient->setBuzzClient($this->buzzMock);
        $locations = $this->apiClient->getLocations();
        $this->assertInstanceOf('ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations', $locations);
        $expectedLocations = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations();
        $location          = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location->setId(32000006);
        $location->setName("International");
        $location->setIsCountry(false);
        $locationArray[] = $location;
        $location        = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location->setId(32000007);
        $location->setName("Afghanistan");
        $location->setIsCountry(true);
        $location->setCountryCode("AF");
        $locationArray[] = $location;
        $expectedLocations->setItems($locationArray);
        $this->assertEquals($expectedLocations, $locations);
    }

    public function testGetLocationsIntegrationTest()
    {
        //reset default timeout from 5 seconds to 20
        $buzz   = $this->apiClient->getBuzzClient();
        $client = $buzz->getClient();
        $client->setTimeout(20);
        $buzz->setClient($client);
        $this->apiClient->setBuzzClient($buzz);
        $locations = $this->apiClient->getLocations();
        $this->assertInstanceOf('ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations', $locations);
    }
}