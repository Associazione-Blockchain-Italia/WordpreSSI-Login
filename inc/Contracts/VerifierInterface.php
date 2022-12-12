<?php

namespace Inc\Contracts;

use Exception;
use Inc\Responses\VerificationResponse;

/**
 * The interface contains the methods that a provider must implement in order to act as a Verifier
 */
interface VerifierInterface
{

    /**
     * The function is used during the LOGIN phase in the pollingVerify to check the status of the verification.
     * The function should be able to return information regarding the following actions:
     * qr-code shown, qr-code-scanned, credential-accepted, credential-verified
     *
     * @param $args: a dictionary containing: the request data, the provider settings, and the plugin settings
     *
     * @return VerificationResponse
     * @throws Exception
     */
    public function getVerification($args): VerificationResponse;

    /**
     * The function is used during the LOGIN phase to request the verification of a credential.
     *
     * @param $args: a dictionary containing: the request data, the provider settings, and the plugin settings
     *
     * @return VerificationResponse
     * @throws Exception
     */
    public function verifyCredential($args): VerificationResponse;

}
