<?php

namespace Inc\Providers\Eassi;

use Inc\Contracts\IssuerInterface;
use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Contracts\ProviderInterface;
use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Fields\CheckboxField;
use Inc\Fields\TextField;
use Inc\Providers\Eassi\ConfigurationTesters\EassiConfigurationTester;
use Inc\Providers\Eassi\Controllers\EassiController;
use Inc\Providers\Eassi\Validators\EassiSettingsValidator;

class EassiProvider implements ProviderInterface
{

    private static string $ID = "ssi_eassi";

    private static string $NAME = "EASSI";

    private static array $CAPABILITIES = [
        ""
    ];

    /**
     * Return the Identifier of the provider;
     * The provider id must follow the pattern 'ssi_providerID'.
     *
     * @return string
     */
    public static function getId(): string
    {
        return self::$ID;
    }

    /**
     * Return the array of capabilities of the plugin
     * @return array
     */
    public static function getCapabilities(): array
    {
        return self::$CAPABILITIES;
    }

    /**
     * Return the Name of the provider
     *
     * @return string
     */
    public static function getName(): string
    {
        return self::$NAME;
    }

    /**
     * Return the array of fields used to set the plugin options.
     * @return array
     */
    public static function getFields(): array
    {
        return [
            new CheckboxField("Debug", "isDebug", self::getId()),
            new TextField("Endpoint URL", "endpointURL", self::getId()),
            new TextField("Application ID", "applicationId", self::getId()),
            new TextField("Shared Credential", "sharedCredential", self::getId()),
            new TextField("Name of Credential", "nameOfCredential", self::getId()),
            new TextField("Identifier", "credentialsMapping.identifier", self::getId()),
            new TextField("Role", "credentialsMapping.role", self::getId()),
        ];
    }

    /**
     * Return the controller that handles all the
     * @return VerifierInterface | IssuerInterface
     */
    public static function getController() {
        return new EassiController();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface
    {
        return new EassiSettingsValidator();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface
    {
        return new EassiConfigurationTester();
    }

}
