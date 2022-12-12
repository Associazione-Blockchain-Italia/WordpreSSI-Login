<?php

namespace Inc\Providers\Eassi\Controllers;

use Inc\Contracts\IssuerInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Exceptions\NetworkException;
use Inc\Providers\Eassi\EassiProvider;
use Inc\Providers\Eassi\Helpers\EassiAPIHelper;
use Inc\Responses\CredentialResponse;
use Inc\Responses\VerificationResponse;
use Inc\Services\UsersService;

class EassiController implements VerifierInterface, IssuerInterface
{

    public function createCredential($args): CredentialResponse
    {
        $curl = curl_init();
        $identifier = $args['identifier'];
        $role = $args['role'];

        UsersService::createUser($identifier, $role, EassiProvider::getName());

        curl_setopt_array($curl, [
            CURLOPT_URL => EassiAPIHelper::getTokenAPIEndpoint($args),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(
                [
                    "type" => $args["nameOfCredential"],
                    "callbackUrl" => EassiAPIHelper::getIssueCallbackURL(),
                    "sub" => "credential-issue-request",
                    "data" => [
                        $args["credentialsMapping.role"] => $role,
                        $args["credentialsMapping.identifier"] => $identifier
                    ]
                ]
            ),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ]);

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new NetworkException(curl_error($curl));
        }

        curl_close($curl);

        return (new CredentialResponse(EassiProvider::getId()))
            ->setRedirectUrl(EassiAPIHelper::getIssueEndpoint($args, $curlResponse))
            ->setCredentialId($curlResponse)
            ->setNextAction('externalPage');
    }

    public function getCredential($args): CredentialResponse
    {
        return (new CredentialResponse(EassiProvider::getId()));
    }

    public function getVerification($args): VerificationResponse
    {
        return (new VerificationResponse(EassiProvider::getId()));
    }

    public function verifyCredential($args): VerificationResponse
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => EassiAPIHelper::getTokenAPIEndpoint($args),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(
                [
                    "type" => $args["nameOfCredential"],
                    "callbackUrl" => EassiAPIHelper::getVerifyCallbackURL(),
                    "sub" => "credential-verify-request",
                ]
            ),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $curlResponse = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new NetworkException(curl_error($curl));
        }
        curl_close($curl);

        return (new VerificationResponse(EassiProvider::getId()))
            ->setRedirectUrl(EassiAPIHelper::getVerifyEndpoint($args, $curlResponse))
            ->setVerificationId($curlResponse)
            ->setNextAction('externalPage');
    }

}
