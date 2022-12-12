<?php

namespace Inc\Helpers;

use Inc\Exceptions\NetworkException;

/**
 * This class contains HTTP methods utilities to communicate with external services through the http protocol.
 */
class HTTPHelper {

    /**
     * @param $curlOptions
     *
     * @return array
     * @throws NetworkException
     */
    public static function executeCURLCall($curlOptions): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $isError = empty($response) || $httpResponseCode >= 400 || !empty($curlError);
        curl_close($curl);
        if ($isError) {
            $err = "Status Code $httpResponseCode.\n CURL Error: $curlError \n Response: $response";
            throw new NetworkException($err);
        }

        return json_decode($response, true);
    }

}
