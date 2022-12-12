<?php

namespace Inc\Providers\Evernym;

class EvernymConstants {

    public static string $WEBHOOK_TABLE = "wordpressi_webhook";

    public static string $CONNECTION_IMG = "";
    public static string $CONNECTION_NAME = "";

    public static function getWebhookQueryInsertStm(): string
    {
        return "INSERT INTO ".self::$WEBHOOK_TABLE."(request_id,thread_id,message_type,body) VALUES (?, ?, ?, ?)";
    }

    public static function compileWebhookQuerySelect($requestId, $msgType): string
    {
        return "SELECT * from ".self::$WEBHOOK_TABLE." where request_id='".$requestId."' and message_type='".$msgType."'";
    }

    public static function compileWebhookQuerySelectBodyByRequestAndMsgType($requestId, $msgType): string
    {
        return "SELECT  body from ".self::$WEBHOOK_TABLE." where request_id='".$requestId."'  and message_type='".$msgType."'";
    }

    public static function compileWebhookQuerySelectBodyByThreadAndMsgType($threadId, $msgType): string
    {
        return "SELECT body from ".self::$WEBHOOK_TABLE." where thread_id='".$threadId."'  and message_type='".$msgType."'";
    }

    public static function compileWebhookQuerySelectBodyFromDID($requestId, $msgType): string {
       return "SELECT body  from ".self::$WEBHOOK_TABLE." where body like '%myDID\":\"".$requestId."\"%' and message_type like '%".$msgType."%'";
    }


}
