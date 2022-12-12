<?php

namespace Inc\Providers\Acapy;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Contracts\ProviderInterface;
use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Fields\TextField;
use Inc\Providers\Acapy\ConfigurationTesters\AcapyConfigurationTester;
use Inc\Providers\Acapy\Controllers\AcapyController;
use Inc\Providers\Acapy\Validators\AcapySettingsValidator;

class AcapyProvider implements ProviderInterface
{

    private static string $ID = "ssi_acapy";

    private static string $NAME = "ACA-Py";

    private static array $CAPABILITIES = [
        ""
    ];

    /**
     * @inheritDoc
     */
    public static function getId(): string
    {
        return self::$ID;
    }

    /**
     * @inheritDoc
     */
    public static function getCapabilities(): array
    {
        return self::$CAPABILITIES;
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return self::$NAME;
    }

    /**
     * @inheritDoc
     */
    public static function getFields(): array
    {
        return [
            new TextField("ACA-Py Admin Endpoint", "serviceEndpoint", self::getId()),
            new TextField("ACA-Py Admin Key", "apikey", self::getId()),
            new TextField("Credential Definition ID", "credentialDefinitionId", self::getId()),
            new TextField("Identifier", "credentialsMapping.identifier", self::getId()),
            new TextField("Role", "credentialsMapping.role", self::getId()),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getController() {
        return new AcapyController();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface
    {
        return new AcapySettingsValidator();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface
    {
        return new AcapyConfigurationTester();
    }

}
