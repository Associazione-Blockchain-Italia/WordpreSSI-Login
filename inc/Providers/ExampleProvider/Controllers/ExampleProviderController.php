<?php

namespace Inc\Providers\ExampleProvider\Controllers;

use Inc\Contracts\IssuerInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Exceptions\NotImplementedException;
use Inc\Providers\ExampleProvider\ExampleProvider;
use Inc\Responses\CredentialResponse;
use Inc\Responses\VerificationResponse;
use Inc\Services\CredentialStatusService;

/**
 * ExampleProvider
 */
class ExampleProviderController implements VerifierInterface, IssuerInterface
{

    /**
     * @inheritDoc
     */
    public function createCredential($args): CredentialResponse
    {
        // This function is called when an user click on the 'SignUp' button.
        // Usually is used to create a connection and return back a qr-code.
        if(!empty($args)){
            throw new NotImplementedException();
        }
        return (new CredentialResponse(ExampleProvider::getId()))
            ->setNextAction('pollingIssue') // possible values pollingIssue | redirectUrl
            ->setCredentialId("")
            ->setOfferURL("some-url-to-display-the-qr-code");
    }

    /**
     * @inheritDoc
     */
    public function getCredential($args): CredentialResponse
    {
        // This function is called during the 'pollingIssue' if the createCredential (previous fn) returns with
        // nextActionValue === 'pollingIssue' and a valid OfferURL
        // The pollingIssue phase starts so the client/frontend will call this function at a regular interval in order to obtain
        // the status of the credential.
        // This is necessary to understand if the qr-code has been displayed, the qr-code has been scanned and if the credential has been accepted.
        //
        // The $args variable of this methods is an associative array where are stored the provider settings (eg if the provider has a field 'apikey'
        // accessing the $args['apikey'] will return the value of the settings, in this case the Api key), and the data sent from the request.
        //
        // If you need to obtain the information regarding the status of the credential while maintaining some sort of state
        // between different API-Calls, you can use the setArgs() method of the CredentialResponse.
        // The special 'args' key is used to maintain information during different polling calls: the values passed to the
        // CredentialResponse->setArgs() function are transferred back in the next polling call.
        // If you call the CredentialResponse->setArgs(['some-key' => 'someValue']) then in the next pollingCall to the getCredential($args),
        // accessing the $args['args'] will produce the previously returned value ['some-key' => 'someValue']

        if(!empty($args)){
            throw new NotImplementedException();
        }
        return (new CredentialResponse(ExampleProvider::getId()))

            ->setCredentialId("")
            ->setOfferURL("some-url-to-display-the-qr-code")
            ->setState(CredentialStatusService::OFFERED) // status of the credential @see CredentialStatusService
            ->setArgs([]); // additional arguments that will be received back in the next call under $args['args'].
    }

    /**
     * @inheritDoc
     */
    public function getVerification($args): VerificationResponse
    {
        // This function is called during the 'pollingVerify' if the createVerification return a response with
        // nextActionValue === 'pollingVerify' and a valid OfferURL.
        //
        // The pollingVerify phase starts and during this phase the client/frontend will call the getVerification function
        // with a fixed interval in order to obtain the status of the credential.
        // This is necessary to understand if the qr-code has been displayed, the qr-code has been scanned and if the credential has been verified.
        //
        // The $args variable of this methods is an associative array where are stored the provider settings (eg if the provider has a field 'apikey'
        // accessing the $args['apikey'] will return the value of the settings, in this case the Api key), and the data sent from the request.
        //
        // If you need to obtain the information regarding the status of the credential while maintaining some sort of state
        // between different API-Calls, you can use the setArgs() method of the VerificationResponse.
        // The special 'args' key is used to maintain information during different polling calls: the values passed to the
        // VerificationResponse->setArgs() function are transferred back in the next polling call.
        // If you call the VerificationResponse->setArgs(['some-key' => 'someValue']) then in the next pollingCall to the getCredential($args),
        // accessing the $args['args'] will produce the previously returned value ['some-key' => 'someValue']

        return (new VerificationResponse(ExampleProvider::getId()))
            ->setVerificationId("")
            ->setVerificationUrl("some-url-to-display-the-qr-code")
            ->setArgs([]) // Additional Arguments
            ->setState(CredentialStatusService::OFFERED); // State of the credential
    }

    /**
     * @inheritDoc
     */
    public function verifyCredential($args): VerificationResponse
    {
        // This function is called when an user click on the 'SignUp' button.
        // Usually is used to create a connection and return back a qr-code.
        return (new VerificationResponse(ExampleProvider::getId()))
            ->setVerificationId("")
            ->setVerificationUrl("some-url-to-display-the-qr-code")
            ->setArgs([]) // Additional Arguments
            ->setIsValid('true-or-false') // true or false
            ->setState(CredentialStatusService::OFFERED); // when accepted should be "Accepted" to exit the PollingVerify
    }

}
