<?php

namespace Inc\Providers\Acapy\Controllers\Impl\Verifier;

use Inc\Exceptions\NetworkException;
use Inc\Helpers\HTTPHelper;
use Inc\Providers\Acapy\AcapyProvider;
use Inc\Providers\Acapy\Helpers\AcapyHelper;
use Inc\Responses\VerificationResponse;
use Inc\Services\CredentialStatusService;

class VerificationGet
{

    /**
     * @param $args
     *
     * @return array | null
     * @throws NetworkException
     */
    private static function checkStatus($args) : ?array {
        // invitation-sent -> qr-code is shown to the user
        // response-sent -> qr-code has been used by the user
        $connectionId = $args['args']['connectionId'];
        $endpoint = "${args['serviceEndpoint']}/connections/$connectionId";
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "GET");
        return HTTPHelper::executeCURLCall($options);
    }

    /**
     * @param $args
     *
     * @return array | null
     * @throws NetworkException
     */
    private static function presentProofSendRequest($args) : ?array{
        $connectionId = $args['args']['connectionId'];
        $endpoint = "${args['serviceEndpoint']}/present-proof/send-request";
        $name = "Proof Request";
        $body = [
            "comment" => "string",
            "connection_id" => $connectionId,
            "proof_request" => [
                "name" => $name,
                "nonce" => "1",
                "requested_attributes" => [
                    "Identifier" => [
                        "name" => $args['credentialsMapping.identifier'],
                        "restrictions" => [
                            [
                                "cred_def_id" => $args["credentialDefinitionId"]
                            ]
                        ],
                    ],
                    "Role" => [
                        "name" => $args['credentialsMapping.role'],
                        "restrictions" => [
                            [
                                "cred_def_id" => $args["credentialDefinitionId"]
                            ]
                        ]
                    ]
                ],
                "requested_predicates" => new \stdClass(),
                "version" => "1.0",
            ],
            "trace" => false
        ];
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "POST", $body);
        return HTTPHelper::executeCURLCall($options);
    }

    /**
     * @param $args
     *
     * @return array | null
     * @throws NetworkException
     */
    private static function presentProofRecords($args) : ?array {
        $presentationExchangeId = $args['args']['presentation_exchange_id'];
        $endpoint = "${args['serviceEndpoint']}/present-proof/records/$presentationExchangeId";
        $options = AcapyHelper::constructCURLOptionsArray($args, $endpoint, "GET");
        return HTTPHelper::executeCURLCall($options);
    }


    public static function getVerification($args): VerificationResponse
    {
        $previousData = $args["args"];
        $previousState = empty($previousData) ? [] : $previousData["state"];
        $response = (new VerificationResponse(AcapyProvider::getId()))
            ->setVerificationId("")
            ->setVerificationUrl("")
            ->setIsValid(boolval("true"))
            ->setState(CredentialStatusService::OFFERED);
        if ($previousState === 'checkStatus' || empty($previousState)) {
            $r = self::checkStatus($args);
            if ($r['rfc23_state'] === 'response-sent') {
                $previousData["state"] = 'presentProofSendRequest';
            }
        }
        else if ($previousState === 'presentProofSendRequest'){
            $r = self::presentProofSendRequest($args);
            $previousData['presentation_exchange_id'] = $r['presentation_exchange_id'];
            $previousData['state'] = 'presentProofRecords';
        }
        else if ($previousState === 'presentProofRecords'){
            $r = self::presentProofRecords($args);
            if($r['state'] === 'verified'){
                $response->setIdentifier($r['presentation']['requested_proof']['revealed_attrs'][$args['credentialsMapping.identifier']]['raw']);
                $response->setIsValid(true);
                $response->setState(CredentialStatusService::ACCEPTED);
            }
        }
        $response->setArgs($previousData);
        return $response;
    }

}


