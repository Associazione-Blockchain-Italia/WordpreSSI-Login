<?php

namespace Inc\Providers\Acapy\Controllers\Impl\Issuer;

use Inc\Exceptions\NetworkException;
use Inc\Helpers\HTTPHelper;
use Inc\Providers\Acapy\AcapyProvider;
use Inc\Providers\Acapy\Helpers\AcapyHelper;
use Inc\Responses\CredentialResponse;
use Inc\Services\CredentialStatusService;

class CredentialGet
{

    /**
     * @throws NetworkException
     */
    private static function checkStatus($args){
        // invitation-sent -> qr-code is shown to the user
        // response-sent -> qr-code has been used by the user
        $connectionId = $args['args']['connectionId'];
        $endpoint = "${args['serviceEndpoint']}/connections/$connectionId";
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "GET");
        return HTTPHelper::executeCURLCall($options);
    }

    /**
     * @param $args
     *
     * @return array
     * @throws NetworkException
     */
    private static function issue($args){
        $connectionId = $args['args']['connectionId'];
        $endpoint = "${args['serviceEndpoint']}/issue-credential/send-offer";
        $credentialDefinitionId = $args['credentialDefinitionId'];
        $credentialsMappingIdentifier = $args['credentialsMapping.identifier'];
        $credentialsMappingRole = $args['credentialsMapping.role'];
        $body = [
            "auto_issue" => true,
            "auto_remove" => true,
            "comment" => "string",
            "connection_id" => $connectionId,
            "cred_def_id" => $credentialDefinitionId,
            "credential_preview" => [
              "@type" => "issue-credential/1.0/credential-preview",
              "attributes" => [
                [
                "name" => $credentialsMappingIdentifier,
                "value" => $args['identifier'],
               ],
                  [
                "name" => $credentialsMappingRole,
                "value" => $args['role']
              ]
              ],
            ],
            "trace" => true,
        ];
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "POST", $body);
        return HTTPHelper::executeCURLCall($options);
    }

    /**
     * @param $args
     *
     * @return CredentialResponse
     * @throws NetworkException
     */
    public static function getCredential($args): CredentialResponse
    {
        $response = self::checkStatus($args);
        $apiState = $response["rfc23_state"];
        $responseStatus = CredentialStatusService::OFFERED;
        if($apiState === "response-sent"){
            try {
                self::issue($args);
                $responseStatus = CredentialStatusService::ISSUED;
            }
            catch (NetworkException $e){
                error_log("Exception on credential creation: ".$e->getMessage());
            }
        }
        return (new CredentialResponse(AcapyProvider::getId()))
            ->setCredentialId($args['args']["connection_id"])
            ->setArgs($args)
            ->setState($responseStatus);
    }

}
