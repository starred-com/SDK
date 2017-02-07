<?php

// Define namespace
namespace StarredSdk\Curl;

/**
 * Curl wrapper class
 *
 * @see http://php.net/manual/en/book.curl.php
 */
class Curl
{

    /**
     * cURL resource handle
     *
     * @var resource
     */
    protected $handle = null;

    /**
     * Init curl connection
     *
     * @return Curl Implements fluent interface
     */
    public function init()
    {

        // Init connection
        $this->handle = \curl_init();

        // Implement fluent interface
        return $this;
    }

    /**
     * Set cURL option value
     *
     * @see http://php.net/curl_setopt
     *
     * @param string $name  Option name
     * @param mixed  $value Option value
     * @return Curl Implements fluent interface
     */
    public function setOption($name, $value)
    {

        // Set option
        \curl_setopt($this->handle, $name, $value);

        // Implement fluent interface
        return $this;
    }

    /**
     * Execute cURL request and return result
     *
     * @return mixed
     */
    public function execute()
    {

        $result = \curl_exec($this->handle);

        // Something went wrong when no result is returned
        if ($result === false) {
            throw new Exception(
                \curl_error($this->handle),
                \curl_errno($this->handle)
            );
        }

        return $result;
    }

    /**
     * Get transfer information for the given option
     *
     * @param string $name Option name to get info for
     * @return mixed
     */
    public function getInfo($name)
    {

        // Return request info
        return \curl_getinfo($this->handle, $name);
    }

    /**
     * Close connection
     *
     * @return Curl Implements fluent interface
     */
    public function close()
    {

        // Close connection
        \curl_close($this->handle);

        // Implement fluent interface
        return $this;
    }
}
