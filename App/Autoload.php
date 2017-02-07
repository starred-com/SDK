<?php

// Define namespace
namespace StarredSdk;

/**
 * Autoload StarredSdk classes
 */
final class Autoload
{

    /**
     * Initialise StarredSdk autoloader
     *
     * @return Autoload
     */
    public function __construct()
    {
        \spl_autoload_register([$this, 'autoload']);
    }

    /**
     * Autoload on StarredSdk classes
     *
     * @param string $className
     * @return void
     */
    private function autoload(string $className)
    {
        // Extract class name and construct file path
        $class = \substr(\str_replace('\\', DIRECTORY_SEPARATOR, $className), 11);
        $filename = \realpath(__DIR__ . DIRECTORY_SEPARATOR . $class . '.php');

        // Require class when file exists
        if (\is_readable($filename)) {
            require_once $filename;
        }
    }
}
