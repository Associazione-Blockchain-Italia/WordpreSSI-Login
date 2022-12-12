<?php

namespace Inc\Contracts;

/**
 * A provider must implement this interface
 *
 */
interface ProviderInterface
{

    /**
     * Return the Identifier of the provider;
     * The provider id follow the pattern 'ssi_providerID'.
     *
     * @return string
     */
    public static function getId(): string;

    /**
     * Return the Name of the provider
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Return the array of capabilities of the provider
     * @return array
     */
    public static function getCapabilities(): array;

    /**
     * Return the array of fields used to set the provider options/settings.
     * @return array
     */
    public static function getFields(): array;

    /**
     * Returns the class that validates the provider settings.
     *
     * @return ProviderSettingsValidatorInterface
     */
    public static function getProviderSettingsValidator(): ProviderSettingsValidatorInterface;

    /**
     * Returns the class that check the provider configuration
     *
     * @return ProviderConfigurationTesterInterface
     */
    public static function getProviderConfigurationTester(): ProviderConfigurationTesterInterface;

    /**
     * Return the Provider controller that handles the requests.
     *
     * @return null | VerifierInterface | IssuerInterface | ConnectionInterface
     */
    public static function getController();

}
