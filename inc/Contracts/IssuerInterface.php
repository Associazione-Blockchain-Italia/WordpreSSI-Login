<?php

namespace Inc\Contracts;

use Exception;
use Inc\Responses\CredentialResponse;

/**
 * This interface contains the methods that a provider must implement in order to Issue Credentials
 */
interface IssuerInterface
{

    /**
     * The function is used in the SIGN-UP phase to create a credential request
     *
     * @param $args: an dictionary containing: the request data, the provider settings, and the plugin settings
     *
     * @return CredentialResponse
     * @throws Exception
     */
    public function createCredential($args): CredentialResponse;

    /**
     * The function is used in the SIGN-UP Phase during the pollingIssue to obtain information regarding the credential
     * This function is used to check if the user has used the code and if the credential has been added to the wallet.
     *
     * @param $args: a dictionary containing: the request data, the provider settings, and the plugin settings
     *
     * @return CredentialResponse
     * @throws Exception
     */
    public function getCredential($args): CredentialResponse;

}
