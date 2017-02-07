<?php

// Define namespace
namespace StarredSdk\Curl\RequestType;

/**
 * PUT Request
 */
class Put implements Request
{

    /**
     * Applies PUT options to the given curl handler
     *
     * @param \StarredSdk\Curl $curlHandler Curl handler
     * @return \StarredSdk\Curl return curl handler
     */
    public function setOptions(\StarredSdk\Curl\Curl $curlHandler)
    {

        // Perform a PUT request
        $curlHandler->setOption(\CURLOPT_CUSTOMREQUEST, 'PUT');

        // Return curl handler
        return $curlHandler;
    }
}
