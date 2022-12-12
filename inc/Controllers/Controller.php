<?php

namespace Inc\Controllers;

use Respect\Validation\Exceptions\ValidationException;

/**
 * A controller is used to handle an http request, validate the request and format the response.
 */
abstract class Controller
{

    /**
     * Each Controller must implement this function in order to handle the request.
     *
     * @return mixed
     */
    public abstract function handle();

    /**
     * Validates the request data with a set of rules
     *
     * @param $request
     * @param $rules
     *
     * @return array
     */
    public function validateRequest($request, $rules): array
    {
        $errors = [];
        foreach ($request as $key => $value) {
			try {
				$rules[ $key ]->check( $value );
			} catch ( ValidationException $exception ) {
				$errors[] = $exception->getMessage();
			}
		}

		return $errors;
	}

    /**
     * Print the response data
     *
     * @param int | string | null $status
     * @param mixed $payload
     *
     * @return void
     */
	public function echoResponse( $status, $payload ) {

		$formatted_response = $status >= 200 && $status <= 400 ?
			$this->formatSuccessResponse( $status, $payload ) :
			$this->formatErrorResponse( $status, $payload );
		echo $formatted_response;
		wp_die();
	}

    /**
     * A success response has a status and a data property
     *
     * @param $status
     * @param $payload
     *
     * @return false|string
     */
	private function formatSuccessResponse( $status, $payload ) {
		$response = [
			"status" => $status,
			"data"   => $payload
		];

		return json_encode( $response );
	}

    /**
     * An error response has a status and an errors property.
     *
     * @param $status
     * @param $errors
     *
     * @return false|string
     */
	private function formatErrorResponse( $status, $errors ) {
		$response = [
			"status" => $status,
			"errors" => $errors
		];

		return json_encode( $response );
	}

}
