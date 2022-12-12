<?php

namespace Inc\Providers\Acapy\Helpers;

class AcapyHelper {

    /**
     * @param $pieces
     *
     * @return string
     */
    private static function formatURL($pieces): string
    {
        return join("/", $pieces);
    }

    /**
     * @param $providerSettings
     *
     * @return string
     */
    public static function getConfigurationTestEndpoint($providerSettings): string
    {
        return self::formatURL([$providerSettings['serviceEndpoint'], "credential-definitions", $providerSettings['credentialDefinitionId']]);
    }

    /**
     * Construct the OptionsArray
     *
     * @param $providerSettings
     * @param $endpoint
     * @param $method
     * @param $body
     *
     * @return array
     */
    public static function constructCURLOptionsArray($providerSettings, $endpoint, $method = "POST", $body = []): array
    {
        $apiKey = $providerSettings['apikey'];
        $curlOptions = [
            CURLOPT_URL => $endpoint,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                "X-API-Key: $apiKey",
                "Accept: application/json",
                "Content-Type: application/*+json"
            ],
        ];
        if (!empty($body)) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        return $curlOptions;
    }

}
