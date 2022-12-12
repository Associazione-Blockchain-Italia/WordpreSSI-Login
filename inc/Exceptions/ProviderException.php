<?php

namespace Inc\Exceptions;

use Exception;

/**
 * Generic Provider Exception
 */
class ProviderException extends Exception
{

    /**
     * @param $message
     */
    public function __construct($message) {
        parent::__construct("An Error has occurred: ${message}");
    }

}
