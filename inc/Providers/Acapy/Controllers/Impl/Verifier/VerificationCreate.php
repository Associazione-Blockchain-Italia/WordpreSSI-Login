<?php

namespace Inc\Providers\Acapy\Controllers\Impl\Verifier;

use Inc\Exceptions\NetworkException;
use Inc\Helpers\HTTPHelper;
use Inc\Providers\Acapy\AcapyProvider;
use Inc\Providers\Acapy\Helpers\AcapyHelper;
use Inc\Responses\VerificationResponse;
use Inc\Services\CredentialStatusService;

class VerificationCreate
{

    /**
     * @param $args
     *
     * @return VerificationResponse
     * @throws NetworkException
     */
    public static function verifyCredential($args): VerificationResponse
    {
        $alias = urlencode("connection_alias");
        $body  = [
            "handshake_protocols"=>["did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/didexchange/1.0"],
            "use_public_did"=>false,
        ];
        $endpoint = "${args['serviceEndpoint']}/connections/create-invitation?alias=$alias&auto_accept=true&multi_use=false&public=false";
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "POST", $body);
        $resp = HTTPHelper::executeCURLCall($options);
        return (new VerificationResponse(AcapyProvider::getId()))
            ->setState(CredentialStatusService::REQUESTED)
            ->setVerificationId($resp['connection_id']?:"")
            ->setVerificationUrl($resp['invitation_url']?:"")
            ->setIsValid(true)
            ->setIdentifier("")
            ->setArgs(["connectionId"=>$resp["connection_id"], "alias"=>$alias])
            ->setNextAction('pollingVerify');
    }

}
