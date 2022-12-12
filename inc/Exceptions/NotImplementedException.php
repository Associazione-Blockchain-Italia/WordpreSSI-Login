<?php

namespace Inc\Exceptions;

use Exception;

/**
 * This Exception should be raised whenever a given use-case is not available yet.
 */
class NotImplementedException extends Exception
{

    /**
     *
     */
    public function __construct() {
        parent::__construct("Method not Implemented!");
    }

}
