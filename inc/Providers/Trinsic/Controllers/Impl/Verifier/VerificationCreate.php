<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Verifier;

use Exception;
use Inc\Exceptions\NetworkException;
use Inc\Responses\VerificationResponse;

class VerificationCreate
{

    /**
     * @throws Exception
     */
    public static function verifyCredential($args): VerificationResponse
    {
        $apiKey = $args['apikey'];

        $curl = curl_init();

        $postFields = [
            "attributes" => [
                [
                    "attributeNames" => [$args['credentialsMapping.identifier'], $args['credentialsMapping.role']],
                    "policyName" => "Credenziale"
                ],
            ],
            "name" => "Autenticazione",
            "version" => "1.0.0"
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.trinsic.id/credentials/v1/verifications/policy",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => [
                "Accept: text/plain",
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

        return VerificationResponseBuilder::build($response)->setNextAction('pollingVerify');
    }

}
