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
    private $disableIntegrationTests;

    public function setUp()
    {
        $this->disableIntegrationTests = false;
        $testKeyLocation               = __DIR__ . '/../../../../../testKey.txt';
        if (!file_exists($testKeyLocation))
        {
            echo $testKeyLocation . " was not found, please create the file in the same location as composer.json" . PHP_EOL;
            $this->disableIntegrationTests = true;
            $this->apiKey                  = "sadYouCantCallTheRealAPI";
        }
        else
        {
            $this->apiKey = file_get_contents($testKeyLocation);
        }
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

    /**
     * @group integration
     */
    public function testGetLocationsIntegrationTest()
    {
        if ($this->disableIntegrationTests)
        {
            $this->markTestSkipped("Integration tests are disabled due to missing test key file");
        }
        //reset default timeout from 5 seconds to 20
        $buzz   = $this->apiClient->getBuzzClient();
        $client = $buzz->getClient();
        $client->setTimeout(20);
        $buzz->setClient($client);
        $this->apiClient->setBuzzClient($buzz);
        $locations = $this->apiClient->getLocations();
        $this->assertInstanceOf('ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations', $locations);
    }

    public function testGetLocationsById()
    {
        $this->responseMock->expects($this->atLeastOnce())->method('getContent')->will($this->returnValue(json_encode(array())));
        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue($this->responseMock));
        $this->apiClient->setBuzzClient($this->buzzMock);


        $locations = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations();
        $location1 = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location1->setId(32000006);
        $location1->setName("International");
        $location1->setIsCountry(false);
        $locationArray[] = $location1;
        $location2       = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location2->setId(32000007);
        $location2->setName("Afghanistan");
        $location2->setIsCountry(true);
        $location2->setCountryCode("AF");
        $locationArray[] = $location2;
        $locations->setItems($locationArray);

        $this->jmsMock->expects($this->atLeastOnce())->method('deserialize')->will($this->returnValue($locations));
        $this->apiClient->setJms($this->jmsMock);

        $returnedLocation = $this->apiClient->getLocationById(32000006);
        $this->assertEquals($location1, $returnedLocation);
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testGetLocationRankingsInvalidLocationId()
    {
        $this->responseMock->expects($this->atLeastOnce())->method('getContent')->will($this->returnValue(json_encode(array())));
        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue($this->responseMock));
        $this->apiClient->setBuzzClient($this->buzzMock);


        $locations = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations();
        $location1 = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location1->setId(32000006);
        $location1->setName("International");
        $location1->setIsCountry(false);
        $locationArray[] = $location1;
        $location2       = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location2->setId(32000007);
        $location2->setName("Afghanistan");
        $location2->setIsCountry(true);
        $location2->setCountryCode("AF");
        $locationArray[] = $location2;
        $locations->setItems($locationArray);

        $this->jmsMock->expects($this->atLeastOnce())->method('deserialize')->will($this->returnValue($locations));
        $this->apiClient->setJms($this->jmsMock);

        $this->apiClient->getLocationRankings(-1, 'players');
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testGetLocationRankingsInvalidRankingId()
    {
//no mocking needed since it'll never hit other code after validation
        $this->apiClient->getLocationRankings(32000006, 'goblins');
    }

    /**
     * @expectedException Exception
     */
    public function testGetLocationRankingsNotImplementedException()
    {
        $this->responseMock->expects($this->atLeastOnce())->method('getContent')->will($this->returnValue(json_encode(array())));
        $this->buzzMock->expects($this->atLeastOnce())->method('get')->will($this->returnValue($this->responseMock));
        $this->apiClient->setBuzzClient($this->buzzMock);


        $locations = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Locations();
        $location1 = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location1->setId(32000006);
        $location1->setName("International");
        $location1->setIsCountry(false);
        $locationArray[] = $location1;
        $location2       = new \ChrisJohnson00\ClashOfClansAPIClient\Entity\Location();
        $location2->setId(32000007);
        $location2->setName("Afghanistan");
        $location2->setIsCountry(true);
        $location2->setCountryCode("AF");
        $locationArray[] = $location2;
        $locations->setItems($locationArray);

        $this->jmsMock->expects($this->atLeastOnce())->method('deserialize')->will($this->returnValue($locations));
        $this->apiClient->setJms($this->jmsMock);

        $this->apiClient->getLocationRankings(32000006, 'players');
    }
}
