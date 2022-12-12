<?php

namespace Inc\Providers\Acapy\Controllers\Impl\Issuer;

use Inc\Exceptions\NetworkException;
use Inc\Helpers\HTTPHelper;
use Inc\Providers\Acapy\AcapyProvider;
use Inc\Providers\Acapy\Helpers\AcapyHelper;
use Inc\Responses\CredentialResponse;

class CredentialCreate
{

    /**
     * @param $args
     *
     * @return CredentialResponse
     * @throws NetworkException
     */
    public static function createCredential($args): CredentialResponse {
        $alias = urlencode("connection_alias");
        $body  = [
            "handshake_protocols"=>["did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/didexchange/1.0"],
            "use_public_did"=>false,
        ];
        $endpoint = "${args['serviceEndpoint']}/connections/create-invitation?alias=$alias&auto_accept=true&multi_use=false&public=false";
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "POST", $body);
        $resp = HTTPHelper::executeCURLCall($options);
        return (new CredentialResponse(AcapyProvider::getId()))
            ->setArgs(["connectionId"=>$resp["connection_id"], "alias"=>$alias])
            ->setCredentialId($resp["connection_id"])
            ->setNextAction('pollingIssue')
            ->setOfferURL($resp["invitation_url"]);
    }

}
