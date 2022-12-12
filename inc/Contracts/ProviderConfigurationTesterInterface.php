<?php

namespace Inc\Contracts;

/**
 * This interface exposes the methods used to verify
 */
interface ProviderConfigurationTesterInterface
{

    /**
     * Check the configuration of a given provider.
     * The argument of the functions args is a dictionary containing the settings of the given provider
     *
     * @param array $args : the associative array containing the provider settings
     *
     * @return array : the list of errors
     */
    public static function checkSettings(array $args): array;

}
