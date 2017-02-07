<?php

// Define namespace
namespace StarredSdk\Config;

/**
 * Authentication configuration value object
 */
final class Auth
{

    /**
     * Load configuration
     *
     * @param array|null $auth
     * @return Auth
     */
    public function __construct(array $auth = null)
    {

        // Lazy load authentication keys
        if (!$auth) {
            $auth = \parse_ini_file(\realpath(__DIR__ . '/Auth.ini'));
        }

        // Set authentication keys
        $this->company  = $auth['company'];
        $this->auth     = $auth['auth'];
    }

    /**
     * Get company key
     *
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Get auth key
     *
     * @return string
     */
    public function getAuth(): string
    {
        return $this->auth;
    }
}
