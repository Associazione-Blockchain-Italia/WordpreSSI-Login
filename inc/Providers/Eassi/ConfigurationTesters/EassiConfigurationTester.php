<?php

namespace Inc\Providers\Eassi\ConfigurationTesters;

use Inc\Contracts\ProviderConfigurationTesterInterface;
use Inc\Exceptions\NetworkException;
use Inc\Providers\Eassi\Helpers\EassiAPIHelper;

class EassiConfigurationTester implements ProviderConfigurationTesterInterface
{

    private static function checkCredentialMapping($providerSettings, $credentialDefinition){
        $errors = [];
        $credentialAttributes = empty($credentialDefinition) ? [] : $credentialDefinition['indySchema']["attributes"];
        $credentialsMappingRoleKey = 'credentialsMapping.role';
        $providerSettingsCredentialsMappingRole = $providerSettings[$credentialsMappingRoleKey];
        $credentialsMappingIdentifierKey = 'credentialsMapping.identifier';
        $providerSettingsCredentialsMappingIdentifier = $providerSettings[$credentialsMappingIdentifierKey];
        if(empty($providerSettingsCredentialsMappingRole)) {
            $errors[$credentialsMappingRoleKey] = __('Error: credential mapping "role" incorrect!');
        }
        else if(!in_array($providerSettingsCredentialsMappingRole, $credentialAttributes )){
            $errors[$credentialsMappingRoleKey] = __('Error: credential mapping "role" not found!');
        }
        if(empty($providerSettingsCredentialsMappingIdentifier)){
            $errors[$credentialsMappingIdentifierKey] = __('Error: credential mapping "identifier" incorrect!');
        }
        else if(!in_array($providerSettingsCredentialsMappingIdentifier, $credentialAttributes)){
            $errors[$credentialsMappingIdentifierKey] = __('Error: credential mapping "identifier" not found!');
        }
        return $errors;
    }

    private static function checkEndpointURL($providerSettings){
        $errors = [];
        if(empty($providerSettings['endpointURL'])) {
            $errors['endpointURL'] = __('Error: "endpointURL" not defined!');
       }
       return $errors;
    }

    private static function checkSharedCredential($providerSettings){
        $errors = [];
        if(empty($providerSettings['sharedCredential'])) {
            $errors['sharedCredential'] = __('Error: "sharedCredential" not defined!');
        }
        return $errors;
    }

    private static function checkNameOfCredential($providerSettings, $credentialDefinition){
        $errors = [];
        if(empty($providerSettings['nameOfCredential'])) {
            $errors['nameOfCredential'] = __('Error: "nameOfCredential" not defined!');
        }
        else if(empty($credentialDefinition)){
            $errors['nameOfCredential'] = __('Error: "nameOfCredential" not found!');
        }
        return $errors;
    }

    private static function checkApplicationID($providerSettings, $apiResponse){
        $errors = [];
        if(empty($providerSettings['applicationId'])) {
            $errors['applicationId'] = __('Error: "applicationId" not defined!');
        }
        else if(empty($apiResponse)){
            $errors["general"] = __('Error: Eassi unreachable or "applicationId" undefined!');
        }
        else if(strval($apiResponse['id']) !== strval($providerSettings['applicationId'])){
            $errors['applicationId'] = __("Error: 'applicationId' not found!");
        }
        return $errors;
    }


    private static function executeCall($args) : ?array {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => EassiAPIHelper::getOrganizationsEndpoint($args),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ]);
        $curlResponse = curl_exec($curl);
        if (curl_errno($curl) || empty($curlResponse)) {
            throw new NetworkException(curl_error($curl));
        }
        curl_close($curl);

        $response = json_decode($curlResponse, true);
        for($i=0; $i<count($response); $i++){
            if(strval($response[$i]["id"])===strval($args['applicationId'])){
                return $response[$i];
            }
        }
        return [];
    }

    private static function extractCredentialDefinition($providerSettings, $apiResponse){
        $credentialDefinition = [];
        $credentialTypes = empty($apiResponse) ? [] : $apiResponse["credentialTypes"];
        for($i=0; $i<count($credentialTypes); $i++){
            if(strval($credentialTypes[$i]["type"])===$providerSettings["nameOfCredential"]){
                $credentialDefinition = $credentialTypes[$i];
                break;
            }
        }
        return $credentialDefinition;
    }

    /**
     * @inheritDoc
     */
    public static function checkSettings(array $args): array
    {
        $errors = [];
        try{
            $errorsEndpointURL = self::checkEndpointURL($args);
            if($errorsEndpointURL){
                return $errorsEndpointURL;
            }
            else{
                $apiResponse = self::executeCall($args);
                $credentialDefinition = self::extractCredentialDefinition($args, $apiResponse);
                $errors = array_merge($errors, self::checkApplicationID($args, $apiResponse));
                $errors = array_merge($errors, self::checkNameOfCredential($args, $credentialDefinition));
                $errors = array_merge($errors, self::checkCredentialMapping($args, $credentialDefinition));
                $errors = array_merge($errors, self::checkSharedCredential($args));
            }
            error_log(json_encode($errors));
        }
        catch (NetworkException $e){
            $errors["network"] = "Unable to contact EASSI: " . $e->getMessage();
        }
        return $errors;
    }

}
