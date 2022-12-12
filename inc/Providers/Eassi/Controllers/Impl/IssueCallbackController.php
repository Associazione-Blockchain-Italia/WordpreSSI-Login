<?php

namespace Inc\Providers\Eassi\Listeners;

require_once "../../../../../../../../wp-config.php";
require_once "../../../../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Inc\Services\ProviderService;

class IssueCallbackController {

    public static function handle($params){
        $token = $params["token"];
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];
        $decoded = json_decode($payload, true);
        $type = $decoded['type'];
        $status = $decoded['status'];
        try {
            $providerSettings = ProviderService::getProviderSettings('ssi_eassi');
            $isDebug = $providerSettings['isDebug'] === "1" || $providerSettings['isDebug'] === 1;
	    $nameOfCredential = $providerSettings['nameOfCredential'];

            $sharedSecret = $providerSettings['sharedCredential'];
            JWT::$leeway = 180;
            JWT::decode($token, new Key($sharedSecret, 'HS256'));

            if($isDebug){
                ?>
                <strong>TOKEN    : </strong><?php echo $token ?><br>
                <strong>HEADER   : </strong><?php echo $header ?><br>
                <strong>PAYLOAD  : </strong><?php echo $payload ?><br>
                <strong>SIGNATURE: </strong><?php echo $signatureProvided ?><br>
                <strong>PAYLOAD  : </strong><?php echo $payload ?><br>
                <br>
                <strong><?php
                    echo $type===$nameOfCredential && $status==='success' ? 'Credential Issued' : "An Error has occurred! Must delete user!"
                    ;?>
                </strong>
                <br>
                <a href="<?php echo wp_login_url(); ?>">Login</a>
                <?php
            }
            else{
                wp_redirect(wp_login_url());
                die;
            }
        }
        catch (\Exception $e){
            echo "Error: Invalid Signature! ".$e->getMessage();
        }
    }

}

IssueCallbackController::handle($_GET);
