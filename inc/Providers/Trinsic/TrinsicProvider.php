<?php

namespace Inc\Providers\Trinsic;

use Inc\Contracts\ConnectionInterface;
use Inc\Contracts\IssuerInterface;
use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Contracts\ProviderInterface;
use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Fields\CheckboxField;
use Inc\Fields\TextField;
use Inc\Providers\Trinsic\ConfigurationTesters\TrinsicConfigurationTester;
use Inc\Providers\Trinsic\Controllers\TrinsicController;
use Inc\Providers\Trinsic\Validators\TrinsicSettingsValidator;

class TrinsicProvider implements ProviderInterface
{

    private static string $ID = "ssi_trinsic";

    private static string $NAME = "Trinsic";

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
            new TextField("API Key", "apikey", self::getId()),
            new TextField("Credential Definition ID", "credentialDefinitionId", self::getId()),
            new TextField("Identifier", "credentialsMapping.identifier", self::getId()),
            new TextField("Role", "credentialsMapping.role", self::getId()),
            new CheckboxField("Activate Shortcode", "shortcode", self::getId()),
            new TextField("Invitation URL", "invitationUrl", self::getId(), '')
        ];
    }

    /**
     * Return the controller that handles all the
     * @return VerifierInterface | IssuerInterface | ConnectionInterface
     */
    public static function getController() {
        return new TrinsicController();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface
    {
        return new TrinsicSettingsValidator();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface
    {
        return new TrinsicConfigurationTester();
    }

}
