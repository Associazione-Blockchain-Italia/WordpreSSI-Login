<?php

namespace Inc\Providers\ExampleProvider\ConfigurationTesters;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Exceptions\NotImplementedException;

class ExampleProviderConfigurationTester implements ProviderConfigurationTesterInterface
{

    /**
     * @inheritDoc
     */
    public static function checkSettings(array $args): array
    {
        /*
         * This function is used to check the configuration of a given provider.
         *
         * This ensure that all the settings provided for a provider (the value of the fields)
         * are valid and can be used.
         *
         * This function should be used to understand, for example, if the api token is valid,
         * if the credentials mapping is correct or if the api endpoint is reachable.
         *
         * Ideally a message for each field is returned but you can also return an array with the first
         * error with the invalid field and an explanation.
         *
         */
       throw new NotImplementedException();
    }

}
