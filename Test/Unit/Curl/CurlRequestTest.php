<?php

// Define namespace
namespace StarredSdkTest\Unit\Curl;

// Bootstrap
require_once \realpath(\dirname(__FILE__) . '/../../../App/Autoload.php');

/**
 * Curl request test case
 *
 * @coversDefaultClass \StarredSdk\Curl\Request
 * @covers ::<protected>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        new \StarredSdk\Autoload();
    }

    /**
     * Test GET request
     *
     * @uses \StarredSdk\Curl\RequestType\Get
     *
     * @covers ::setCurlHandler
     * @covers ::setUrl
     * @covers ::getRequest
     * @covers ::getResponse
     * @covers ::getResponseCode
     * @test
     */
    public function getRequest()
    {

        // Define response
        $response = [
            'status' => 200,
            'message' => 'Starred api is up'
        ];

        // Get curl stub
        $curlStub = $this->getCurlStub($response, '200');

        // Set curl handler
        $curlRequest = new \StarredSdk\Curl\Request($curlStub);
        $curlRequest

            // Set endpoint
            ->setEndpoint('https://api.starred.com/heartbeat')

            // Perform request
            ->getRequest();

        // Response should match
        $this->assertEquals(
            $response,
            $curlRequest->getResponseAsArray(),
            'Response does not match'
        );

        // Response code should match
        $this->assertEquals(
            200,
            $curlRequest->getResponseCode(),
            'Response code does not match'
        );
    }

    /**
     * Test POST request
     *
     * @uses \StarredSdk\Curl\RequestType\Post
     *
     * @covers ::setCurlHandler
     * @covers ::setUrl
     * @covers ::setRequestData
     * @covers ::postRequest
     * @covers ::getResponse
     * @covers ::getResponseCode
     * @test
     */
    public function postRequest()
    {

        // Define response
        $response = [
            'status' => 200,
            'message' => 'success'
        ];

        // Get curl stub
        $curlStub = $this->getCurlStub($response, '200');

        // Get curl request
        $curlRequest = new \StarredSdk\Curl\Request($curlStub);
        $curlRequest

            // Set endpoint
            ->setEndpoint('https://api.starred.com/heartbeat')

            // Set request data
            ->setRequestData(['auth' => 'YOUR_TOKEN', 'company' => 1])

            // Perform request
            ->postRequest();

        // Response should match
        $this->assertEquals(
            $response,
            $curlRequest->getResponseAsArray(),
            'Response does not match'
        );

        // Response code should match
        $this->assertEquals(
            200,
            $curlRequest->getResponseCode(),
            'response code does not match'
        );
    }

    /**
     * Test PUT request
     *
     * @uses \StarredSdk\Curl\RequestType\Put
     *
     * @covers ::setCurlHandler
     * @covers ::setUrl
     * @covers ::setRequestData
     * @covers ::putRequest
     * @covers ::getResponse
     * @covers ::getResponseCode
     * @test
     */
    public function putRequest()
    {

        // Define response
        $response = [
            'status' => 200,
            'message' => 'Success.'
        ];

        // Get curl stub
        $curlStub = $this->getCurlStub($response, '200');

        // Set curl request
        $curlRequest = new \StarredSdk\Curl\Request($curlStub);
        $curlRequest

            // Set endpoint
            ->setEndpoint('https://api.starred.com/heartbeat')

            // Set request data
            ->setRequestData(['auth' => 'YOUR_TOKEN', 'company' => 1])

            // Perform request
            ->putRequest();

        // Response should match
        $this->assertEquals(
            $response,
            $curlRequest->getResponseAsArray(),
            'Response does not match'
        );

        // Response code should match
        $this->assertEquals(
            200,
            $curlRequest->getResponseCode(),
            'response code does not match'
        );
    }

    /**
     * When a request could not be completed for some reason a cURL exception
     * should be thrown
     *
     * @uses \StarredSdk\Curl\Curl
     *
     * @expectedException \StarredSdk\Curl\Exception
     * @test
     */
    public function requestFails()
    {

        // Create an empty curl request which always fails
        $curlClient = new \StarredSdk\Curl\Curl();
        $curlClient->init();

        // Get curl request
        $curlRequest = new \StarredSdk\Curl\Request($curlClient);

        // Do request
        $curlRequest->getRequest();
    }

    /**
     * When a request returns invalid json the process should be interrupted
     *
     * @expectedException \StarredSdk\Curl\Exception
     * @test
     */
    public function requestReturnsInvalidJson()
    {

        // Get curl handler stub
        $curlStub = $this->getMockBuilder('\StarredSdk\Curl\Curl')
            ->setMethods(['execute', 'close'])
            ->getMock();

        // Return json response
        $curlStub->expects($this->once())
            ->method('execute')
            ->will(
                // Return an invalid json string by stripping the last 2
                // characters off
                $this->returnValue(
                    \substr(\json_encode(array('message' => 'foo')), 0, -2)
                )
            );

        // Close should be called before the exception is thrown
        $curlStub->expects($this->once())
            ->method('close');

        // Get curl request
        $curlRequest = new \StarredSdk\Curl\Request($curlStub);

        // Do request
        $curlRequest->getRequest();

        // Get response as an array (which should be possible when response was
        // valid JSON only)
        $curlRequest->getResponseAsArray();
    }

    /**
     * Generate stub with expected outcome
     *
     * @param array  $response     Expected response data
     * @param string $responseCode Expected return code
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCurlStub(array $response, $responseCode)
    {

        // Get curl handler stub
        $curlStub = $this->getMockBuilder('\StarredSdk\Curl\Curl')
            ->setMethods(['init', 'setOption', 'execute', 'getInfo', 'close'])
            ->getMock();

        // Return self on init
        $curlStub->expects($this->once())
            ->method('init')
            ->will($this->returnSelf());

        // Return self on setOption
        $curlStub->expects($this->any())
            ->method('setOption')
            ->will($this->returnSelf());

        // Return json response
        $curlStub->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(\json_encode($response)));

        // Return self on init
        $curlStub->expects($this->once())
            ->method('getInfo')
            ->will($this->returnValue($responseCode));

        // Return self on close
        $curlStub->expects($this->once())
            ->method('close')
            ->will($this->returnSelf());

        // Return created stub
        return $curlStub;
    }
}
