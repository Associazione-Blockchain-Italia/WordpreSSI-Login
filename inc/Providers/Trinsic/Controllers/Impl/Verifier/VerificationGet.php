<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Verifier;

use Exception;
use Inc\Exceptions\NetworkException;
use Inc\Responses\VerificationResponse;

class VerificationGet
{

    /**
     * @throws Exception
     */
    public static function getVerification($args): VerificationResponse
    {
        $apiKey = $args['apikey'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.trinsic.id/credentials/v1/verifications/" . $args['verificationId'],
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

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new NetworkException($err);
        }

        $response = json_decode($result, true);

        return VerificationResponseBuilder::build($response);
    }

}


