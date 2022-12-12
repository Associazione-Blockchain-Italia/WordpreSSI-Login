<?php

namespace Inc\Contracts;

use Exception;
use Inc\Responses\ConnectionResponse;

/**
 * This interface implements the methods used to create and obtain a shortcode connection
 */
interface ConnectionInterface
{

    /**
     * The function request the creation of a connection
     *
     * @param $providerSettings
     *
     * @return ConnectionResponse
     * @throws Exception
     */
    public function createConnection($providerSettings): ConnectionResponse;

    /**
     * The function allows to get all the active connection in a given state
     *
     * @param $providerSettings
     * @param $state
     *
     * @return ConnectionResponse
     * @throws Exception
     */
    public function getConnections($providerSettings, $state): ConnectionResponse;

}
