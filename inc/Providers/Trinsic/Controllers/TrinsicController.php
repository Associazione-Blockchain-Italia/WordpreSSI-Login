<?php

namespace Inc\Providers\Trinsic\Controllers;

use Inc\Contracts\ConnectionInterface;
use Inc\Contracts\IssuerInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Providers\Trinsic\Controllers\Impl\Connection\ConnectionCreate;
use Inc\Providers\Trinsic\Controllers\Impl\Connection\ConnectionGet;
use Inc\Providers\Trinsic\Controllers\Impl\Issuer\CredentialCreate;
use Inc\Providers\Trinsic\Controllers\Impl\Issuer\CredentialGet;
use Inc\Providers\Trinsic\Controllers\Impl\Verifier\VerificationCreate;
use Inc\Providers\Trinsic\Controllers\Impl\Verifier\VerificationGet;
use Inc\Responses\ConnectionResponse;
use Inc\Responses\CredentialResponse;
use Inc\Responses\VerificationResponse;

class TrinsicController implements VerifierInterface, IssuerInterface, ConnectionInterface
{

    public function createCredential($args): CredentialResponse
    {
		return CredentialCreate::createCredential($args);
    }

    public function getCredential($args): CredentialResponse
    {
        return CredentialGet::getCredential($args);
    }

    public function getVerification($args): VerificationResponse
    {
        return VerificationGet::getVerification($args);
    }

    public function verifyCredential($args): VerificationResponse
    {
        return VerificationCreate::verifyCredential($args);
    }

    public function createConnection($providerSettings): ConnectionResponse
    {
        return ConnectionCreate::createConnection($providerSettings);
    }

    public function getConnections($providerSettings, $state): ConnectionResponse
    {
       return ConnectionGet::getAllConnections($providerSettings, $state);
    }

}
