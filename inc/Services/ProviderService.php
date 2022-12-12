<?php

namespace Inc\Services;

use Inc\Contracts\ProviderInterface;
use Inc\Providers\Acapy\AcapyProvider;
use Inc\Providers\Eassi\EassiProvider;
use Inc\Providers\Evernym\EvernymProvider;
use Inc\Providers\Trinsic\TrinsicProvider;

/**
 * The class helps with common operations regarding the providers
 */
class ProviderService
{

    /**
     * Get all the registered providers.
     *
     * @return array
     */
    public static function getProviders(): array
    {
        return [
            TrinsicProvider::getId() => new TrinsicProvider(),
            EassiProvider::getId() => new EassiProvider(),
            EvernymProvider::getId() => new EvernymProvider(),
            AcapyProvider::getId() => new AcapyProvider(),
        ];
    }

    /**
     * The function returns a list of providers as simple objects.
     * Each element is represented as {id: provider_id, name: provider_name, capabilities: [c1,c2...]}
     * The parameter $providersToFilter represents a list of providers id used to filter the results set.
     * If the parameter is null (default value) then all the providers are returned.
     *
     * @param array|null $providersToFilter : the list of providers ids to retrieve as json representation
     *
     * @return array
     */
    public static function getProvidersAsJson(?array $providersToFilter = null): array
    {
        $result = [];
        $providersList = self::getProviders();
        $elements = $providersToFilter === null ? array_keys($providersList) : $providersToFilter;
        /** @var ProviderInterface $po */
        foreach ($elements as $pid){
            if(!empty($providersList[$pid])){
                $result[] = [
                    "id" => $pid,
                    "name" => $providersList[$pid]::getName(),
                    "capabilities" => $providersList[$pid]::getCapabilities()
                ];
            }
        }
        return $result;
    }

    /**
     * Return a dictionary containing for each provider its status (active = true).
     *
     * @return array : dictionary that maps the provider id to its status eg. ["ssi_trinsic" => true]
     */
    public static function getProvidersStatus(): array
    {
        $plugin_settings = get_option('ssi_plugin_settings');
        if ($plugin_settings && $plugin_settings['active_providers']) {
            return $plugin_settings['active_providers'];
        }

        return [];
    }

    /**
     * Return the ids of the providers that are active.
     *
     * @return array
     */
    public static function getActiveProviders(): array
    {
        $providerStatus = self::getProvidersStatus();
        $result = [];
        foreach ($providerStatus as $provider => $state) {
            if (boolval($state)) {
                $result[] = $provider;
            }
        }

        return $result;
    }

    /**
     * Check if the provider specified is active or not.
     *
     * @param string|null $providerId : the id of the provider to check
     *
     * @return bool
     */
    public static function isProviderActive(?string $providerId): bool
    {
        $providersStatus = self::getProvidersStatus();
        $keys = array_keys($providersStatus);
        for ($i = 0; $i < count($keys); ++$i) {
            if ($keys[$i] === $providerId && boolval($providersStatus[$keys[$i]])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the list of settings defined for the provider id passed as parameter
     *
     * @param string|null $providerId : the provider id
     *
     * @return array : the dictionary containing the settings for the given provider
     */
    public static function getProviderSettings(?string $providerId): array
    {
        $options = get_option($providerId);

        return $options ? $options : [];
    }

    /**
     * Get a single provider by id
     *
     * @param string|null $providerId string: the provider id to retrieve
     *
     * @return ProviderInterface
     */
    public static function getProvider(?string $providerId): ProviderInterface
    {
        return self::getProviders()[$providerId];
    }

}
