<?php

namespace Inc\Providers\Eassi\Listeners;

require_once "../../../../../../../../wp-config.php";
require_once "../../../../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Inc\Services\ProviderService;

class VerifyCallbackController {

    public static function handle($params){
        $token = $params["token"];
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];
        $decoded = json_decode($payload, true);
        $type = $decoded['type'];
        $status = $decoded['status'];
        $data = $decoded['data'];

        try{
            $providerSettings = ProviderService::getProviderSettings('ssi_eassi');
            $isDebug = $providerSettings['isDebug'] === "1" || $providerSettings['isDebug'] === 1;
            $sharedSecret = $providerSettings['sharedCredential'];
            $identifier = $data[$providerSettings['credentialsMapping.identifier']];
            $role = $data[$providerSettings['credentialsMapping.role']];
            $nameOfCredential = $providerSettings['nameOfCredential'];
            JWT::$leeway = 180;
            JWT::decode($token, new Key($sharedSecret, 'HS256'));

            if($type === $nameOfCredential && $status === 'success'){
                \Inc\Services\UsersService::authenticateUser($identifier);
            }
            if($isDebug){
                ?>
                <strong> TOKEN     : </strong> <?php echo $token; ?> <br/>
                <strong> HEADER    : </strong> <?php echo $header; ?> <br/>
                <strong> PAYLOAD   : </strong> <?php echo $payload; ?> <br/>
                <strong> SIGNATURE : </strong> <?php echo $signatureProvided; ?> <br/>
                <strong> DECODED   : </strong> <?php echo json_encode($decoded); ?> <br/>
                <strong> TYPE      : </strong> <?php echo $type; ?> <br/>
                <strong> STATUS    : </strong> <?php echo $status; ?> <br/>
                <strong> DATA      : </strong> <?php echo json_encode($data); ?> <br/>
                <strong> IDENTIFIER: </strong> <?php echo $identifier; ?> <br/>
                <strong>      ROLE : </strong> <?php echo $role; ?> <br/>
                <br/>
                <strong> <?php echo ($type==$nameOfCredential)&&($status=="success") ? 'CREDENTIALS VERIFIED' : 'CREDENTIALS NOT VERIFIED'; ?> </strong>
                <br>
                <a href="<?php echo home_url(); ?>">HOME</a>
                <?php
            }
            else{
                wp_redirect(home_url());
                die;
            }
        }
        catch (\Exception $e){
           echo "Error: ". $e->getMessage();
        }

    }

}

VerifyCallbackController::handle($_GET);
