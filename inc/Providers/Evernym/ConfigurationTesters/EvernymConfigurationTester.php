<?php

namespace Inc\Providers\Evernym\ConfigurationTesters;

use Inc\Contracts\ProviderConfigurationTesterInterface;

class EvernymConfigurationTester implements ProviderConfigurationTesterInterface
{

    /**
     * @inheritDoc
     */
    public static function checkSettings(array $args): array
    {
       return [];
    }

}
