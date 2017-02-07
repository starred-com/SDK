<?php

// Define namespace
namespace StarredSdk\Curl\RequestType;

/**
 * GET Request
 */
class Get implements Request
{

    /**
     * Applies GET options to the given curl handler
     *
     * @param \StarredSdk\Curl $curlHandler Curl handler
     * @return \StarredSdk\Curl return curl handler
     */
    public function setOptions(\StarredSdk\Curl\Curl $curlHandler)
    {

        // Return curl handler
        return $curlHandler;
    }
}
