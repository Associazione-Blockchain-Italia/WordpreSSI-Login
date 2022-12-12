<?php

ini_set("log_errors", 1);
ini_set("display_errors", 0);
ini_set("error_log", "log.txt");
error_reporting(E_ALL);

error_log("setup.php");


function submit_evernym_test()
{

   $curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://vas.pps.evernym.com/api/A4js9EAQVwX6Q3CPXxjQCX/configs/0.6/123',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "@id": "1",
    "@type": "did:sov:123456789abcdefghi1234;spec/configs/0.6/UPDATE_COM_METHOD",
    "comMethod": {
        "id": "xykz3",
        "value": "https://f2a0-94-34-35-71.ngrok.io/wordpress/wp-content/plugins/SSIPlugin/inc/Providers/Evernym/Public/webhook.php",
        "type": 2,
        "packaging": {
            "pkgType": "plain"
        }
    }
}',
  CURLOPT_HTTPHEADER => array(
    'X-API-KEY: 4nG1VmWoz9Rq4gVQaysSMB2AFPALNS4Py1puniSaHicz:gDdazskCezsUgYRresqUscvzbM2QJ7bW7xLXqVMW4BFwR4MKx54XE4Qkfv2HZbjsh9P4fAnZ4auXoVjfepRTHCX',
    'Content-Type: application/json'
  ),
));

// disabled ssl verification curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    file_put_contents(time() . "-resp.txt", print_r($response, true));

}

submit_evernym_test();

?>
