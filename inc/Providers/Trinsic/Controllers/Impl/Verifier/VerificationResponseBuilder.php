<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Verifier;

use Inc\Providers\Trinsic\Controllers\Impl\CredentialStatuses\CredentialStatusMapper;
use Inc\Providers\Trinsic\TrinsicProvider;
use Inc\Responses\VerificationResponse;

class VerificationResponseBuilder {

    public static function build($response) : VerificationResponse {
        return (new VerificationResponse(TrinsicProvider::getId()))
            ->setState(CredentialStatusMapper::getCredentialStatus($response['state']))
            ->setVerificationId($response['verificationId']?:"")
            ->setVerificationUrl($response['verificationRequestUrl']?:"")
            ->setIsValid(boolval($response['isValid']?:""))
            ->setIdentifier(self::getIdentifier($response['proof'])?:"");
    }

    private static function getIdentifier($proof){
        if($proof && $proof['Credenziale'] && $proof['Credenziale']['attributes']){
            return $proof['Credenziale']['attributes']['Identifier'];
        }
        return null;
    }


}
