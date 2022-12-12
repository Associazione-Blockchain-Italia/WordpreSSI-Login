<?php

namespace Inc\Exceptions;

use Exception;

/**
 * InternalServer Exception: This exception should be raised whenever an unhandled server Exception is not handled.
 */
class InternalServerErrorException extends Exception
{

    /**
     * @param $message
     */
    public function __construct($message) {
        if (is_array($message)){
            $message = join(" \n", $message);
        }
        parent::__construct($message);
    }

}
