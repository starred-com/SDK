<?php

// Define namespace
namespace StarredSdk\Curl\RequestType;

/**
 * Request type interface
 */
interface Request
{

    /**
     * Applies REQUEST options to the given curl handler
     *
     * @param \StarredSdk\Curl\Curl $curlHandler Curl handler
     * @return \StarredSdk\Curl\Curl return curl handler
     */
    public function setOptions(\StarredSdk\Curl\Curl $curlHandler);
}
