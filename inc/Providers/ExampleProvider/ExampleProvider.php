<?php

namespace Inc\Providers\ExampleProvider;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Contracts\ProviderInterface;
use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Fields\TextField;
use Inc\Providers\ExampleProvider\ConfigurationTesters\ExampleProviderConfigurationTester;
use Inc\Providers\ExampleProvider\Controllers\ExampleProviderController;
use Inc\Providers\ExampleProvider\Validators\ExampleProviderSettingsValidator;

class ExampleProvider implements ProviderInterface
{

    // This is the id of the provider, use the format ssi_<providerId>
    private static string $ID = "ssi_example_provider";

    // This is the name of the provider shown in the Admin Menu, The Title of the settings page
    // // and displayed in the button of the active providers.
    private static string $NAME = "Example Provider";

    // A list of capabilities for the provider Verifier, Issuer, and ShortCode
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
            new TextField("Example Field", "example_field", self::$ID)
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getController() {
        return new ExampleProviderController();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface
    {
        return new ExampleProviderSettingsValidator();
    }

    /**
     * @inheritDoc
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface
    {
        return new ExampleProviderConfigurationTester();
    }

}
