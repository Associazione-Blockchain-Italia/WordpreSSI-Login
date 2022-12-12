<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Issuer;

use Inc\Providers\Trinsic\Controllers\Impl\CredentialStatuses\CredentialStatusMapper;
use Inc\Providers\Trinsic\TrinsicProvider;
use Inc\Responses\CredentialResponse;

class CredentialResponseBuilder {

    public static function build($response) : CredentialResponse
    {
        return (new CredentialResponse(TrinsicProvider::getId()))
            ->setState(CredentialStatusMapper::getCredentialStatus($response['state']))
            ->setCredentialId($response['credentialId'])
            ->setOfferURL($response['offerUrl']);
    }

}
