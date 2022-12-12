<?php

namespace Inc\Providers\Evernym;

use Inc\Contracts\ConnectionInterface;
use Inc\Contracts\IssuerInterface;
use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Contracts\ProviderInterface;
use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Fields\TextField;
use Inc\Providers\Evernym\ConfigurationTesters\EvernymConfigurationTester;
use Inc\Providers\Evernym\Controllers\EvernymController;
use Inc\Providers\Evernym\Validators\EvernymSettingsValidator;

class EvernymProvider implements ProviderInterface
{

    private static string $ID = "ssi_evernym";

    private static string $NAME = "Evernym";

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
            new TextField("Service Endpoint", "serviceEndpoint", self::getId()),
            new TextField("Domain DID", "domainDID", self::getId(), ''),
            new TextField("WebHook URL", "webhookURL", self::getId()),
            new TextField("API Key", "apikey", self::getId()),
            new TextField("Credential Definition ID", "credentialDefinitionId", self::getId()),
            new TextField("Identifier", "credentialsMapping.identifier", self::getId()),
            new TextField("Role", "credentialsMapping.role", self::getId()),
        ];
    }

    /**
     * Return the controller that handles all the
     * @return VerifierInterface | IssuerInterface | ConnectionInterface
     */
    public static function getController() {
        return new EvernymController();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface
    {
        return new EvernymSettingsValidator();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface
    {
        return new EvernymConfigurationTester();
    }

}
