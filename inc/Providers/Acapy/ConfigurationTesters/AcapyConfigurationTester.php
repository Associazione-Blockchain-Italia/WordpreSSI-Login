<?php

namespace Inc\Providers\Acapy\ConfigurationTesters;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Exceptions\NetworkException;
use Inc\Helpers\HTTPHelper;
use Inc\Providers\Acapy\Helpers\AcapyHelper;

class AcapyConfigurationTester implements ProviderConfigurationTesterInterface
{

    /**
     * @inheritDoc
     */
    public static function checkSettings(array $args): array
    {
        $errors = [];
        try{
            $configurationTestEndpoint = AcapyHelper::getConfigurationTestEndpoint($args);
            $optArray = AcapyHelper::constructCURLOptionsArray($args, $configurationTestEndpoint, "GET");
            HTTPHelper::executeCURLCall($optArray);
            // Todo check more settings...
        }
        catch (NetworkException $e){
            $msg = $e->getMessage();
            $errors["network"] = "ACAPY Unreachable ${msg}";
        }
        return $errors;
    }

}
