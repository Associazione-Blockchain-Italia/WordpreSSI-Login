<?php

namespace Inc\Providers\Acapy\Controllers;

use Inc\Contracts\IssuerInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Providers\Acapy\Controllers\Impl\Issuer\CredentialCreate;
use Inc\Providers\Acapy\Controllers\Impl\Issuer\CredentialGet;
use Inc\Providers\Acapy\Controllers\Impl\Verifier\VerificationCreate;
use Inc\Providers\Acapy\Controllers\Impl\Verifier\VerificationGet;
use Inc\Responses\CredentialResponse;
use Inc\Responses\VerificationResponse;

class AcapyController implements VerifierInterface, IssuerInterface
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

}
