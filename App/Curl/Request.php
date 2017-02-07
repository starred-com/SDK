<?php

namespace StarredSdk\Curl;

/**
 * Curl Request Handler
 */
class Request
{

    /**
     * Curl client instance
     *
     * @var Curl
     */
    protected $curlHandler = null;

    /**
     * POST data to send with the request
     *
     * @var array
     */
    protected $requestData = [];

    /**
     * Raw response to the cURL request
     *
     * @var string
     */
    protected $rawResponse = null;

    /**
     * Request response code
     *
     * @var int
     */
    protected $responseCode = 0;

    /**
     * Whether to send JSON body data or
     *
     * @var boolean
     */
    protected $sendJson = false;

    /**
     * Initialise request
     *
     * @param Curl|null $curlHandler Curl handler to use (defaults to new Curl instance)
     * @return Request
     */
    public function __construct(Curl $curlHandler = null)
    {
        if (!$curlHandler) {
            $curlHandler = new Curl();
        }

        // Init curl handler
        $curlHandler->init()

            // Return a string instead of outputting the result directly
            ->setOption(\CURLOPT_RETURNTRANSFER, true)

            // Be sure to check SSL
            //->setOption(\CURLOPT_SSL_VERIFYHOST, 2)
            //->setOption(\CURLOPT_SSL_VERIFYPEER, true);

            // Disable SSL in order to test on staging.. :-(
            ->setOption(\CURLOPT_SSL_VERIFYHOST, false)
            ->setOption(\CURLOPT_SSL_VERIFYPEER, false);

        $this->curlHandler = $curlHandler;
    }

    /**
     * Set the request endpoint
     *
     * @param string $endpoint Url endpoint
     * @return Request Implements fluent interface
     */
    public function setEndpoint(string $endpoint): Request
    {

        // Set endpoint and request header
        $this->curlHandler->setOption(\CURLOPT_URL, $endpoint);

        // Implements fluent interface
        return $this;
    }

    /**
     * Set request data. This will send a POST instead of a GET request
     *
     * @param array $data Request data to set
     * @return Request Implements fluent interface
     */
    public function setRequestData(array $data): Request
    {

        // Set request data
        $this->requestData = $data;

        // Implements fluent interface
        return $this;
    }

    /**
     * Perform GET request
     *
     * @return Request Implements fluent interface
     */
    public function getRequest(): Request
    {

        // Send a GET request
        $this->send(new RequestType\Get());

        // Implement fluent interface
        return $this;
    }

    /**
     * Perform POST request
     *
     * @param bool $sendJson
     * @return Request Implements fluent interface
     */
    public function postRequest(bool $sendJson = false): Request
    {

        // Whether or not to send a json response
        $this->sendJson = $sendJson;

        // Send a POST request
        $this->send(new RequestType\Post());

        // Implement fluent interface
        return $this;
    }

    /**
     * Perform PUT request
     *
     * @return Request Implements fluent interface
     */
    public function putRequest(): Request
    {

        // Send a PUT request
        $this->send(new RequestType\Put());

        // Implement fluent interface
        return $this;
    }

    /**
     * Get request response as an array
     *
     * @return array
     */
    public function getResponseAsArray(): array
    {
        // If we have anything in response - trying to decode it from JSON
        if (empty($this->rawResponse)) {
            return [];
        }

        // Convert json result into an array
        $decodedResponseData = \json_decode($this->rawResponse, true);

        // Assert response data could be decoded
        if (null === $decodedResponseData) {
            throw new Exception(
                'Response data could not be decoded, ' . \json_last_error_msg()
                .  ': ' . \PHP_EOL . $this->rawResponse
            );
        }

        return $decodedResponseData;
    }

    /**
     * Get HTTP response code
     *
     * @return int
     */
    public function getResponseCode(): int
    {

        // Return response code
        return (int) $this->responseCode;
    }

    /**
     * Perform request
     *
     * @return CurlRequest Implements fluent interface
     */
    protected function send(RequestType\Request $request)
    {

        // Apply options based on request type (PUT|POST|GET etc.)
        $this->curlHandler = $request->setOptions($this->curlHandler);

        // Add request data when available
        if (!($request instanceof RequestType\Get)) {
            if ($this->sendJson) {
                $this->prepareToSendJson();
            } else {
                $this->prepareToSendForm();
            }
        }

        // Get response data and code
        $this->rawResponse  = $this->curlHandler->execute();
        $this->responseCode = $this->curlHandler->getInfo(\CURLINFO_HTTP_CODE);

        // Close curl connection
        $this->curlHandler->close();

        return $this;
    }

    /**
     * Prepare headers and post data to be send as a regular form post
     *
     * @return void
     */
    protected function prepareToSendForm()
    {

        // Build post data query string
        $postData = \http_build_query($this->requestData, null, '&');

        // Specify we send a url encoded form
        $this->curlHandler->setOption(
            \CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            )
        )->setOption(
            \CURLOPT_POSTFIELDS,
            $postData
        );
    }

    /**
     * Prepare headers and data to be send as a JSON post
     *
     * @return void
     */
    protected function prepareToSendJson()
    {

        // Json encode post data
        $postData = \json_encode($this->requestData);

        // Specify we send json data
        $this->curlHandler->setOption(
            \CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . \strlen($postData)
            )
        )->setOption(
            \CURLOPT_POSTFIELDS,
            $postData
        );
    }
}
