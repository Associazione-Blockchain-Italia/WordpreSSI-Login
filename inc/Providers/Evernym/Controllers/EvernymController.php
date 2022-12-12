<?php

namespace Inc\Providers\Evernym\Controllers;

use Inc\Contracts\ConnectionInterface;
use Inc\Contracts\IssuerInterface;
use Inc\Contracts\VerifierInterface;
use Inc\Exceptions\NotImplementedException;
use Inc\Exceptions\ProviderException;
use Inc\Providers\Evernym\EvernymConstants;
use Inc\Providers\Evernym\EvernymProvider;
use Inc\Responses\ConnectionResponse;
use Inc\Responses\CredentialResponse;
use Inc\Responses\VerificationResponse;
use Inc\Services\ProviderService;
use Inc\Services\UsersService;

use phpDocumentor\Reflection\Types\This;

class EvernymController implements VerifierInterface, IssuerInterface, ConnectionInterface
{

    const CONTENT_TYPE_APPLICATION_JSON = 'Content-Type: application/json';
    const PROOF_SENT = "_proof_sent";

    public function getApiHeader(){
	// disabled error_log("API ".ProviderService::getProviderSettings('ssi_evernym')['apikey']);
        return "X-API-KEY: ".ProviderService::getProviderSettings('ssi_evernym')['apikey'];
    }

    public function getBaseUrl(){
        return ProviderService::getProviderSettings('ssi_evernym')['serviceEndpoint'];
    }

    public function getCredentialDefinitionId(){
        return ProviderService::getProviderSettings('ssi_evernym')['credentialDefinitionId'];
    }

    public function getIdentifier(){
        return ProviderService::getProviderSettings('ssi_evernym')['credentialsMapping.identifier'];
    }

    public function getRole(){
        return ProviderService::getProviderSettings('ssi_evernym')['credentialsMapping.role'];
    }

    public function getDomainDid(){
        return ProviderService::getProviderSettings('ssi_evernym')['domainDID'];
    }

    public function getApiEndpointRelationship(){
	return $this->getBaseUrl().'/api/'. $this->getDomainDid() . '/relationship/1.0/';	
    }


    public function credentialsMappings(): array
    {
        $settings = ProviderService::getProviderSettings('ssi_evernym');
        return [
            "identifier" => $settings["credentialsMapping.identifier"],
            "role" => $settings["credentialsMapping.role"],
        ];
    }

    public function createCredential($args): CredentialResponse {

        $curl = curl_init();

        $request_id = uniqid();
        $thread_id = uniqid();

        $contenuto = json_encode([
            "@type" => "did:sov:123456789abcdefghi1234;spec/relationship/1.0/create",
            "@id" => $request_id,
            "label" => "WordPreSSI Login",
            "logoUrl" => "<string>"
        ]);

        //TODO configura nome
        //TODO configura url immagine

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getApiEndpointRelationship() . $thread_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $contenuto,
            CURLOPT_HTTPHEADER => array($this->getApiHeader(), self::CONTENT_TYPE_APPLICATION_JSON),
        ));

        $response = curl_exec($curl);

        if (!curl_errno($curl)) {
            $info = curl_getinfo($curl);

            error_log('<br/> Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']);
        } else {
            error_log('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        error_log($contenuto);
        error_log("RESPONSE" . $response);

        /// waiting result of WEBHOOK message "created"
        $risultato = "";
        $numero = 20;
        for ($i = 0; $i < $numero; $i++) {
            $risultato = $this->get_webhook_data($thread_id, "did:sov:123456789abcdefghi1234;spec/relationship/1.0/created");

            error_log("WAIT " . $risultato);

            if ($risultato) {
               break;
            }

            sleep(1);
        }

        if (!$risultato) {
            echo '{ "error": "createConnection: nessuna risposta da webhook"}';
        } else {
            // SECONDA CHIAMATA

            $post = json_decode($risultato, true);

            $did = $post['did'];
            error_log("did" . $did);

            $curl = curl_init();

            $postfields = '{
   "~for_relationship":"' . $did . '",
   "@type":"did:sov:123456789abcdefghi1234;spec/relationship/1.0/connection-invitation"
}'; // , "@id": "'.$request_id.'"

            // disabled error_log("URL2:https://vas.pps.evernym.com/api/xxxxxxxxxxxxxxx/relationship/1.0/" . $thread_id);
            // disabled error_log("SEND2:" . $postfields);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->getApiEndpointRelationship() . $thread_id, //a4.... preso da db //3 deve diventare thid
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => array($this->getApiHeader(), self::CONTENT_TYPE_APPLICATION_JSON),
            ));

            $response = curl_exec($curl);

            error_log("RESPONSE2:" . $response);

            curl_close($curl);

            $risultato = "";
            $numero = 10;
            for ($i = 0; $i < $numero; $i++) {
                $risultato = $this->get_webhook_data($thread_id, "did:sov:123456789abcdefghi1234;spec/relationship/1.0/invitation");

                error_log("WAIT 3 " . $risultato);

                if ($risultato) {
                   break;
                }

                sleep(1);
            }

            if (!$risultato) {
                echo '{ "error": "createConnection2: nessuna risposta da webhook"}';
                error_log('{ "error": "createConnection2: nessuna risposta da webhook"}');
            } else {
                $post = json_decode($risultato, true);

                $inviteURL = $post['inviteURL'];
                $invitationId = $did;

                error_log("invitationId:" . $invitationId . " inviteURL:" . $inviteURL);

                return (new CredentialResponse('ssi_evernym'))
                    ->setCredentialId($invitationId)
                    ->setNextAction('pollingIssue')
                    ->setOfferURL($inviteURL);
            }
        } // seconda chiamata
        throw new NotImplementedException();
    }

    public function getCredential($args): CredentialResponse
    {
        $credentialId = $args['credentialId'];
        error_log("getConnection ". $credentialId);

        // leggi da tabella webhook e se hai trovato la scansione di quel codice,

        // devo cercare un messaggio

        // TODO meccanismo di indirezione per non rivleare il did
        // devo cercare un messaggio response-sent con il valore myDID = $connectionId

        $risultato = $this->get_webhook_data_body($credentialId, "spec/connections/1.0/response-sent");

        error_log("WAIT4".$risultato);

	$str=rand();
        $identifier= md5($str);

        $role = "subscriber";

        UsersService::createUser($identifier, $role, EvernymProvider::getName());

        $response = new CredentialResponse('ssi_evernym');
        $response->setCredentialId($credentialId);
        if ($risultato) {
            $this->offerCredentialEvernym($credentialId, $identifier, $role);
            $response->setState('Issued');
        }
        else {
            $response->setState('Presented');
        }

        return $response;
    }

    function proofStatus($verificationId)
    {
        error_log("proofStatus ".$verificationId);

        // se la proof è validated... allora
        // cerca nel webhook una did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result

        $threadId = get_option("threadid_".$verificationId. self::PROOF_SENT);

        $risultato = $this->get_webhook_data_thread($threadId,"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result");

        error_log("getVerification result ".$risultato);

        if (!$risultato)
        {
            $risultato2 = $this->get_webhook_data_thread($threadId,"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/problem-report");

            error_log("getVerification result2 ".$risultato2);

            if (!$risultato2)
            {
                return 0;
            }
            else
            {
                return 1; // rifiutata
            }
        }
        else
        {
            $oggetto = json_decode($risultato,true);

            //{"verification_result":"ProofValidated","requested_presentation":{"revealed_attrs":{"name":{"identifier_index":0,"value":"4489ff780fdfssi"},"degree":{"identifier_index":0,"value":"subscriber"}},"self_attested_attrs":{},"unrevealed_attrs":{},"predicates":{},"identifiers":[{"schema_id":"SdSiXtX2gkrnr7QNMwNFN6:2:Diploma a145035a:0.1","cred_def_id":"SdSiXtX2gkrnr7QNMwNFN6:3:CL:210473:latest"}]},"@type":"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result","@id":"8c13ead5-0661-49db-87f7-a20aee9f1350","~thread":{"thid":"6163681da6c02","sender_order":1,"received_orders":{"54gjJKPDmHVAzmXzd4mKr8":0}}}

            error_log("verification_result1:".$oggetto["verification_result"]);
            error_log("verification_result2 id:".$oggetto["requested_presentation"]["revealed_attrs"][$this->getIdentifier()]["value"]);
            error_log("verification_result3 role:".$oggetto["requested_presentation"]["revealed_attrs"][$this->getRole()]["value"]);


            return 2;
            //TODO DEVO PASSARE I VALORI DEI CLAIM
        }


    }

    function proofIdentifierValue($verificationId)
    {
        error_log("proofIdentifierValue".$verificationId);

        // se la proof è validated... allora
        // cerca nel webhook una did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result

        $threadId = get_option("threadid_".$verificationId. self::PROOF_SENT);

        $risultato = $this->get_webhook_data_thread($threadId,"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result");

        error_log("getVerification result ".$risultato);

        if (!$risultato)
        {

            $risultato2 = $this->get_webhook_data_thread($threadId,"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/problem-report");

            error_log("getVerification result2 ".$risultato2);

            if (!$risultato2)
            {
                return 0;
            }
            else
            {
                return 1; // rifiutata
            }


        }
        else
        {

            $oggetto = json_decode($risultato,true);

            //{"verification_result":"ProofValidated","requested_presentation":{"revealed_attrs":{"name":{"identifier_index":0,"value":"4489ff780fdfssi"},"degree":{"identifier_index":0,"value":"subscriber"}},"self_attested_attrs":{},"unrevealed_attrs":{},"predicates":{},"identifiers":[{"schema_id":"SdSiXtX2gkrnr7QNMwNFN6:2:Diploma a145035a:0.1","cred_def_id":"SdSiXtX2gkrnr7QNMwNFN6:3:CL:210473:latest"}]},"@type":"did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/presentation-result","@id":"8c13ead5-0661-49db-87f7-a20aee9f1350","~thread":{"thid":"6163681da6c02","sender_order":1,"received_orders":{"54gjJKPDmHVAzmXzd4mKr8":0}}}


            error_log("verification_result1:".$oggetto["verification_result"]);
            error_log("verification_result2 id:".$oggetto["requested_presentation"]["revealed_attrs"][$this->getIdentifier()]["value"]);
            error_log("verification_result3 role:".$oggetto["requested_presentation"]["revealed_attrs"][$this->getRole()]["value"]);


            return $oggetto["requested_presentation"]["revealed_attrs"][$this->getIdentifier()]["value"];
        }


    }



    function connectionStatus($verificationId)
     {
         // leggi da tabella webhook e se hai trovato la scansione di quel codice,

         // devo cercare un messaggio

         // devo cercare un messaggio response-sent con il valore myDID = $connectionId

         $risultato = $this->get_webhook_data_body($verificationId,"spec/connections/1.0/response-sent");

         error_log("WAIT2 ".$risultato);

         if ($risultato) {
             // connessione OK
             return 1;
         }
         else
         {
             return 0;
         }
     }

    public function getVerification($args): VerificationResponse
    {
        $verificationId = $args["verificationId"];
        error_log("getVerificationConnection ".$verificationId);
        $response = (new VerificationResponse('ssi_evernym'))->setState('Waiting')->setIsValid(false);

        /**
         * 1. Login
         * 2. Chiamiamo Evernym per ottenere l'invite url
         * 3. Attendiamo la scansione
         * 4. Utente scansione e invia richiesta di prova
         * 5. Attendi esito richiesta di prova da evernym
         */

        $proofStatus = $this->proofStatus($verificationId);
        if ($proofStatus == 0) // proof not yet complete
        {
            // non fare nulla
            // devo ancora ricevere l'esito della connessione?
            if ($this->connectionStatus($verificationId))
            {
                error_log ("LEGGO did_".$verificationId. self::PROOF_SENT);
                if (get_option("did_".$verificationId. self::PROOF_SENT)==0)
                {
                    $this->sendProof($verificationId);
                }
            }
        }
        else if ($proofStatus ==1) // proof rejected
        {
            $response->setState('Rejected');
        }
        else if ($proofStatus==2) // proof accepted
	{

 	    $identifier = $this->proofIdentifierValue($verificationId);
	    if (strlen($identifier)>3)
	    {	    
              $response->setState('Accepted');
              $response->setIsValid(true);
	      $response->setIdentifier($identifier);
	    }
	    else
	    {
	      $response->setState('Rejected');
	    }

        }
        return $response;
    }

    public function verifyCredential($args): VerificationResponse
    {
        return $this->verifyCredentialEvernym();
    }

    public function createConnection($providerSettings): ConnectionResponse
    {
        throw new NotImplementedException();
    }

    public function getConnections($providerSettings, $state): ConnectionResponse
    {
        throw new NotImplementedException();
    }

    ####

    private function get_webhook_data($request_id,$message_type)
    {
        $result = 0;
        $dblink = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD,  DB_NAME);
        /* If connection fails throw an error */

        if (mysqli_connect_errno()) {
            error_log( "Could  not connect to database1 : Error: ".mysqli_connect_error());
            return 0;
        }
        $sqlquery = EvernymConstants::compileWebhookQuerySelectBodyByRequestAndMsgType($request_id, $message_type);
	error_log($sqlquery);
        $valore = "";
        if ($result = mysqli_query($dblink, $sqlquery))
        {
            $conto = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $conto++;
                $valore =  $row["body"];
            }

            // trovato, aggiorno?

            error_log("valore 2:".$valore);

            /* free result set */
            mysqli_free_result($result);

            if ($conto>0) {
                $result = $valore;
            }

        }
	error_log("ritorno get_webhook_data:".$valore);
        return $valore;
    }

    private function get_webhook_data_thread($request_id,$message_type)
    {
        $result = 0;
        $dblink = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD,  DB_NAME);

        /* If connection fails throw an error */

        if (mysqli_connect_errno()) {
            error_log( "Could  not connect to database: Error: ".mysqli_connect_error());
            return 0;
        }

        $sqlquery = EvernymConstants::compileWebhookQuerySelectBodyByThreadAndMsgType($request_id, $message_type);
	error_log($sqlquery);
        $valore = "";
        if ($result = mysqli_query($dblink, $sqlquery))
        {
            $conto = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $conto++;
                $valore =  $row["body"];
            }
            // trovato, aggiorno?

            error_log("valore:".$valore);

            /* free result set */
            mysqli_free_result($result);

            if ($conto>0) {
                error_log(	 "valore trovato nella tabella webhook!");
                $result = $valore;
            }

        }
	error_log("ritorno get_webhook_data_thread:".$valore);
        return $valore;
    }

    private function get_webhook_data_body($request_id,$message_type)
    {
        $result = 0;
        $dblink = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD,  DB_NAME);
        /* If connection fails throw an error */
        if (mysqli_connect_errno()) {
            error_log( "Could  not connect to database: Error: ".mysqli_connect_error());
            return 0;
        }
        $sqlquery = EvernymConstants::compileWebhookQuerySelectBodyFromDID($request_id, $message_type);
        error_log($sqlquery);
        $valore = "";
        if ($result = mysqli_query($dblink, $sqlquery))
        {
            $conto = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $conto++;
                $valore =  $row["body"];
            }
            // trovato, aggiorno?
            error_log("valore:".$valore);
            /* free result set */
            mysqli_free_result($result);
            if ($conto>0) {
                error_log(	 "valore trovato nella tabella webhook!");
                $result = $valore;
            }
        }
	error_log("ritorno get_webhook_data_body:".$valore);
        return $valore;
    }

    private function offerCredentialEvernym($credentialId, $identifier, $role)
    {
        error_log("OFFER:".$identifier);
        $connectionId = $credentialId;
        $baseurl = $this->getBaseUrl();
        $domainDID = $this->getDomainDid();
        $request_id = uniqid();
        $thread_id = uniqid();
        $url = $baseurl . '/api/'.$domainDID.'/issue-credential/1.0/'.$thread_id;
        $curl = curl_init();
        $json = '{
    "@type": "did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/issue-credential/1.0/offer",
    "@id": "'.$request_id.'",
    "~for_relationship": "'.$connectionId.'",
    "cred_def_id": "'.$this->getCredentialDefinitionId().'",
    "credential_values": { "'.$this->getIdentifier().'": "'.$identifier.'",
        "'.$this->getRole().'": "'.$role.'" },
    "price": 0,
    "comment": "WordPreSSI Login",
    "auto_issue": true
}';
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, //a4.... preso da db //3 deve diventare thid',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$json,
            CURLOPT_HTTPHEADER => array($this->getApiHeader(), self::CONTENT_TYPE_APPLICATION_JSON),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    error_log("URL:".$url);
    error_log("OFFER1:".$json);
    error_log("OFFER2:".$response);

}

    /**
     * @throws ProviderException
     */
    private function verifyCredentialEvernym()
    {

        $curl = curl_init();

        $request_id = uniqid();
        $thread_id = uniqid();

        $contenuto = '{
        "@type": "did:sov:123456789abcdefghi1234;spec/relationship/1.0/create",
        "@id": "'.$request_id.'",
        "label": "WordPreSSI Login",
        "logoUrl": "<string>" 
    }';

        //TODO configura nome
        //TODO configura url immagine

	$url = $this->getBaseUrl() . '/api/'. $this->getDomainDid() . '/relationship/1.0/'.$thread_id;
	error_log("calling1 ".$url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, //a4.... preso da db //3 deve diventare thid
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $contenuto ,
            CURLOPT_HTTPHEADER => array($this->getApiHeader(), self::CONTENT_TYPE_APPLICATION_JSON),
        ));

        $response = curl_exec($curl);

        if(!curl_errno($curl))
        {
            $info = curl_getinfo($curl);
            error_log ('<br/> Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']);
        }
        else
        {
            error_log ('Curl error: ' . curl_error($curl));
        }


        curl_close($curl);
        // disabled error_log("URL:https://vas.pps.evernym.com/api/xxxxxxxxxxxxxxx/relationship/1.0/".$thread_id);
        error_log($contenuto);
        error_log("RESPONSE".$response);


        /// waiting for WEBHOOK message "created"
        $risultato = "";
        $numero = 20;
        for ($i=0; $i<$numero; $i++) {
            $risultato = $this->get_webhook_data($thread_id,"did:sov:123456789abcdefghi1234;spec/relationship/1.0/created");
            error_log("WAIT ".json_encode($risultato));
            if ($risultato) {
                break;
            }
            sleep(1);
        }

        if (!$risultato)
        {
            echo '{ "error": "verifyCredential: nessuna risposta da webhook"}';
	    throw new ProviderException('No Response');
        }
        else
        {

            // SECONDA CHIAMATA

            $post = json_decode($risultato,true);

            $did = $post['did'];
            error_log("did".$did);

            $curl = curl_init();

            $postfields = '{
   "~for_relationship":"'.$did.'",
   "@type":"did:sov:123456789abcdefghi1234;spec/relationship/1.0/connection-invitation"
}'; // , "@id": "'.$request_id.'"

            // disabled error_log("URL2:https://vas.pps.evernym.com/api/xxxxxxxxxxxxxx/relationship/1.0/".$thread_id);
            error_log("SEND2:".$postfields);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->getApiEndpointRelationship().$thread_id, //a4.... preso da db //3 deve diventare thid
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$postfields,
                CURLOPT_HTTPHEADER => array(
                    $this->getApiHeader(),
                    self::CONTENT_TYPE_APPLICATION_JSON 
                ),
            ));

            $response = curl_exec($curl);

            error_log("RESPONSE2:".$response);

            curl_close($curl);
            $risultato = "";
            $numero = 10;
            for ($i=0; $i<$numero; $i++) {
                $risultato = $this->get_webhook_data($thread_id,"did:sov:123456789abcdefghi1234;spec/relationship/1.0/invitation");
                error_log("WAIT2 ".$risultato);
                if ($risultato) {
                    break;
                }
                sleep(1);
            }

            if (!$risultato) {
                echo '{ "error": "verifyCredential2: nessuna risposta da webhook"}';
                error_log( '{ "error": "verifyCredential2: nessuna risposta da webhook"}');
            }
            else {

                $post = json_decode($risultato,true);

                $inviteURL = $post['inviteURL'];
                $invitationId = $did;

                update_option("did_".$invitationId. self::PROOF_SENT, 0);
                error_log("did_".$invitationId."_proof_sent SET TO 0");

                return (new VerificationResponse('ssi_evernym'))
                    ->setNextAction('pollingVerify')
                    ->setVerificationUrl($inviteURL)
                    ->setVerificationId($invitationId);
            }

            throw new ProviderException('No Response');

        }

        return null;

    }

    private function sendProof($identifier)
    {


        $threadId = uniqid();

        error_log("sendProof ".$identifier. " ".$threadId);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getBaseUrl() . '/api/'. $this->getDomainDid() . '/present-proof/1.0/'.$threadId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
    "@type": "did:sov:BzCbsNYhMrjHiqZDTUASHg;spec/present-proof/1.0/request",
    "~for_relationship": "'.$identifier.'",
    "name": "Proof of diploma",
    "proof_attrs": [
      {
        "name": "'.$this->getIdentifier().'",
        "restrictions": [],
        "self_attest_allowed": false
      },
      {
        "name": "'.$this->getRole().'",
        "restrictions": [],
        "self_attest_allowed": false
      }
    ]
  }',
            CURLOPT_HTTPHEADER => array($this->getApiHeader(), self::CONTENT_TYPE_APPLICATION_JSON),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        error_log( $response);

        update_option("did_".$identifier. self::PROOF_SENT, 1);
        update_option("threadid_".$identifier. self::PROOF_SENT, $threadId);
        error_log( "did_".$identifier. self::PROOF_SENT);
        error_log( "threadid_".$identifier."_proof_sent: ".$threadId);

        return $threadId ;

    }
}


