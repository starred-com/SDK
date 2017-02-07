<?php

// Define namespace
namespace StarredSdk\Curl\RequestType;

/**
 * POST Request
 */
class Post implements Request
{

    /**
     * Applies POST options to the given curl handler
     *
     * @param \StarredSdk\Curl $curlHandler Curl handler
     * @return \StarredSdk\Curl return curl handler
     */
    public function setOptions(\StarredSdk\Curl\Curl $curlHandler)
    {

        // Perform a POST request
        $curlHandler->setOption(\CURLOPT_POST, true);

        // Return curl handler
        return $curlHandler;
    }
}
