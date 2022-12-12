<?php

require_once "../../../../../../../wp-config.php";

ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_reporting(E_ALL);

error_log("WEBHOOK");

$input = file_get_contents('php://input');
$post = json_decode($input,true);

$message_type = $post['@type'];

$request_id = "";
$thread_id = "";

$messageUpdate = $message_type == "did:sov:123456789abcdefghi1234;spec/configs/0.6/COM_METHOD_UPDATED";
$messageCreatedOrInvitation = $message_type =="did:sov:123456789abcdefghi1234;spec/relationship/1.0/created";
$messageCreatedOrInvitation = $messageCreatedOrInvitation || $message_type=="did:sov:123456789abcdefghi1234;spec/relationship/1.0/invitation";
$THREAD_NAME = '~thread';
if ($messageUpdate)
{
    $request_id = $post['id'];
}
else if ($messageCreatedOrInvitation)
{
    $request_id = $post[$THREAD_NAME]['thid'];
    $thread_id = $post[$THREAD_NAME]['thid'];
} else
{
    $request_id = $post['@id'];
    $thread_id = $post[$THREAD_NAME]['thid'];
}

//Enable to see all the requests file_put_contents(time()."-req.txt", print_r($post,true));

error_log("W1:". $request_id. " " .$message_type);

$dblink = mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME);
/* If connection fails throw an error */
if (mysqli_connect_errno()) {
    error_log( "Could not connect to database: Error: ".mysqli_connect_error());
    exit();
}

$sqlquery = \Inc\Providers\Evernym\EvernymConstants::compileWebhookQuerySelect($request_id, $message_type);

if ($result = mysqli_query($dblink, $sqlquery)) {
    $conto = $result->num_rows;
    // if a simil value is already found, update?
    error_log("CONTO:".$conto);
    /* free result set */
    mysqli_free_result($result);
    if ($conto>0)
    {
        error_log(	 "esiste, aggiorno");
        ?>
        { }
        <?php
    }

    else { // non trovato, inserisco

        error_log( "inserisco");

        $stmt = $dblink->prepare(\Inc\Providers\Evernym\EvernymConstants::getWebhookQueryInsertStm());

        error_log( print_r($stmt,true));

        if(!$stmt) {
            error_log('Error: '.$dblink->error);
        }

        /* Bind parameters */
        $stmt->bind_param('ssss',$request_id,$thread_id,$message_type,$input);

        /* Execute statement */
        $res = $stmt->execute();
        error_log(print_r($res,true));
        error_log(print_r($stmt,true));
        $stmt->close();
        ?>
        { }
        <?php
    }


}

/* close connection */
mysqli_close($dblink);

?>

