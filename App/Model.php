<?php

namespace StarredSdk;

/**
 * API endpoint model
 *
 * Every endpoint has its own model
 */
class Model
{

    /**
     * @var Curl\Request
     */
    private $request;

    /**
     * @var Config\Auth
     */
    private $authConfig;

    /**
     * @var string
     */
    private $domain;

    /**
     * Set request object and auth configuration to process the request with
     * the correct credentials to the right endpoint domain
     *
     * @param Config\Auth  $auth
     * @param Curl\Request $request
     * @param string       $domain  Endpoint domain (defaults to https://api.starred.com)
     */
    public function __construct(
        Config\Auth $authConfig = null,
        Curl\Request $request = null,
        string $domain = 'https://api.starred.com'
    ) {

        // Lazy load config when not set
        if (!$authConfig) {
            $authConfig = new Config\Auth();
        }
        $this->authConfig = $authConfig;

        // Lazy load request when not set
        if (!$request) {
            $request = new Curl\Request();
        }
        $this->request = $request;

        // Set domain
        $this->domain = $domain;
    }

    /**
     * Prepare and send a request to the given endpoint
     *
     * @param string $endpoint
     * @param array  $data
     * @return array
     */
    protected function send(string $endpoint, array $data = []): array
    {
        // Get authentication keys and add them to the dataset
        $data['company'] = $this->authConfig->getCompany();
        $data['auth'] = $this->authConfig->getAuth();

        // Execute request
        $this->request->setEndpoint($this->domain . '/' . $endpoint);
        $this->request->setRequestData($data);
        $this->request->postRequest();

        // Return request result
        return $this->request->getResponseAsArray();
    }
}
