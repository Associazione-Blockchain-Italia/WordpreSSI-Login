<?php

namespace Inc\Exceptions;

use Exception;

/**
 * A network Exception should be raised whenever there's a communication error between the server and a third-party service
 * exposed over HTTP.
 */
class NetworkException extends Exception
{

    /**
     * @param $message
     */
    public function __construct($message) {
        parent::__construct("A network exception has occurred: ${message}");
    }

}
