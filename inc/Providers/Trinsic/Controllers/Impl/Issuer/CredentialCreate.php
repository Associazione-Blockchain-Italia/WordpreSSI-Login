<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Issuer;

use Exception;
use Inc\Exceptions\NetworkException;
use Inc\Responses\CredentialResponse;

class CredentialCreate
{

    /**
     * @throws Exception
     */
    public static function createCredential($args): CredentialResponse
    {
        $apiKey = $args['apikey'];
        $credentialDefinitionId = $args['credentialDefinitionId'];
        $identifier = $args['identifier'];
        $role = $args['role'];

        $curl = curl_init();

        $postFields = [
            "credentialValues" => [
                $args['credentialsMapping.identifier'] => $identifier,
                $args['credentialsMapping.role'] => $role,
            ],
            "definitionId" => $credentialDefinitionId,
            "automaticIssuance" => true
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.trinsic.id/credentials/v1/credentials",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Bearer " . $apiKey,
                "Content-Type: application/*+json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new NetworkException($err);
        }

        $response = json_decode($response, true);

        return CredentialResponseBuilder::build($response)->setNextAction('pollingIssue');
    }

}
