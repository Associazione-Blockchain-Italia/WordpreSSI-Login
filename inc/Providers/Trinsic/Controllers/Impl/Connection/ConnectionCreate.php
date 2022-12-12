<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Connection;

use Exception;
use Inc\Exceptions\NetworkException;
use Inc\Providers\Trinsic\TrinsicProvider;
use Inc\Responses\ConnectionResponse;

class ConnectionCreate
{

    /**
     * @throws Exception
     */
    public static function createConnection($providerSettings): ConnectionResponse
    {
        $apiKey = $providerSettings['apikey'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.trinsic.id/credentials/v1/connections",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"multiParty\":true,\"name\":\"WordpreSSI\"}",
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

        $resp = json_decode($response,true);

        return (new ConnectionResponse(TrinsicProvider::getId()))
            ->setConnectionInvitationUrl($resp['invitationUrl']);
    }

}
