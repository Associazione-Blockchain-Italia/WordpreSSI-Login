<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\Messaging;

class TrinsicChatController {

	static function getAllMessages( $connectionId ) {
		$apikey = get_option( "ssi_trinsic" )['apikey'];

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://api.trinsic.id/credentials/v1/messages/connection/' . $connectionId );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );


		$headers   = array();
		$headers[] = 'Authorization: ' . $apikey;
		$headers[] = 'Accept: application/json';
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$result = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			echo 'Error:' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $result, true );
	}

	function sendMessage(): void {
		$connectionId = $_POST['connectionId'];
		$message      = $_POST['message'];
		$apikey       = get_option( "ssi_trinsic" )['apikey'];

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://api.trinsic.id/credentials/v1/messages' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "{\"connectionId\": \"$connectionId\",\"text\": \"$message\"}" );

		$headers   = array();
		$headers[] = 'Authorization: ' . $apikey;
		$headers[] = 'Content-Type: application/*+json';
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$result = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			echo 'Error:' . curl_error( $ch );
		}
		curl_close( $ch );

		$response = json_decode( $result, true );

		echo $response;
	}

	static function getAllConnections() {
		$apikey = get_option( "ssi_trinsic" )['apikey'];

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://api.trinsic.id/credentials/v1/connections' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );


		$headers   = array();
		$headers[] = 'Authorization: ' . $apikey;
		$headers[] = 'Accept: application/json';
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$result = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			echo 'Error:' . curl_error( $ch );
		}
		curl_close( $ch );

		return json_decode( $result, true );
	}
}