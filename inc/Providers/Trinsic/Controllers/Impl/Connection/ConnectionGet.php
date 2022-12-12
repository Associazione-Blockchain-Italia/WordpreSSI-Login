<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Connection;

use Exception;
use Inc\Exceptions\NetworkException;
use Inc\Providers\Trinsic\TrinsicProvider;
use Inc\Responses\ConnectionResponse;

class ConnectionGet
{

    /**
     * @throws Exception
     */
    public static function getAllConnections($providerSettings, $state): ConnectionResponse
    {
        $apiKey = $providerSettings['apikey'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.trinsic.id/credentials/v1/connections?state=".$state,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Bearer " . $apiKey
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new NetworkException($err);
        }
        $resp=json_decode($response,true);

        return (new ConnectionResponse(TrinsicProvider::getId()))
            ->setAllConnections($resp);
    }
}
