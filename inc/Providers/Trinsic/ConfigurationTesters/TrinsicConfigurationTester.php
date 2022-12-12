<?php

namespace Inc\Providers\Trinsic\ConfigurationTesters;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Exceptions\NetworkException;

class TrinsicConfigurationTester implements ProviderConfigurationTesterInterface
{

    /**
     * @inheritDoc
     */
    public static function checkSettings(array $args): array
    {
        $configErrors = [];
        try {
            $endpoint = "https://api.trinsic.id/credentials/v1/definitions/credentials/" . $args['credentialDefinitionId'];
            $apiKey = $args["apikey"];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Accept: text/plain",
                    "Authorization: Bearer " . $apiKey
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
               throw new NetworkException("Impossible to test configuration: ${$err}");
            }
            $jsonResponse = json_decode($response, true);

            if ($jsonResponse === null) {
                $configErrors["apikey"] = __('Error: Wrong Api Key');
            }
            else if ($jsonResponse["statusCode"] && intval($jsonResponse["statusCode"])>=400){
                $configErrors["network"] = __("Service Not Available!");
            }
            else {
                $configErrors = self::extractErrors($args, $jsonResponse);
            }
        } catch (\Exception $e) {
            $configErrors["network"] = __("Network Exception: Impossible to Ckeck Configuration!");
        }

        return $configErrors;
    }

    private static function extractErrors($args, $jsonResponse){
        $configErrors = [];
        if (isset($jsonResponse['type']) && $jsonResponse['type'] == 'NotFoundException') {
            $configErrors["credentialDefinitionId"] = __('Error: Wrong Credential Definition Id');
        }
        if (!in_array($args['credentialsMapping.identifier'], $jsonResponse['attributes'])) {
            $configErrors["credentialMapping.identifier"] = __('Error: Credential Mapping is incorrect (Identifier)');
        }
        if (!in_array($args['credentialsMapping.role'], $jsonResponse['attributes'])) {
            $configErrors["credentialMapping.role"] = __('Error: Credential Mapping is incorrect (Role)');
        }
        return $configErrors;
    }

}
