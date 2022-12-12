<?php

namespace Inc\Controllers;

use Exception;
use Inc\Contracts\IssuerInterface;
use Inc\Services\ProviderService;
use Respect\Validation\Rules;

/**
 * The controller handles the requests to create a connection
 */
class CredentialCreateController extends Controller {

    /**
     * The function handle a credential create request.
     *
     * A request for create a connection must specify: the provider, the role and the identifier.
     * After the validation, the provider is resolved and if it can act as issuer the request is handled.
     *
     * @return void
     */
	public function handle() {
		$request          = [
			'provider'   => $_POST['provider'],
			'role'       => $_POST['role'],
			'identifier' => $_POST['identifier'],
//			'args'       => $_POST['args']
		];
		$rules            = [
			'provider'   => new Rules\AllOf( new Rules\NotBlank(),
				new Rules\In( ProviderService::getActiveProviders() ) ),
			'role'       => new Rules\AllOf( new Rules\NotBlank(), new Rules\In( [ 'subscriber' ] ) ),
			'identifier' => new Rules\AllOf( new Rules\NotBlank() ),
//			'args'       => new Rules\AllOf( )
		];
		$validationErrors = $this->validateRequest( $request, $rules );
		if ( $validationErrors !== null && sizeof( $validationErrors ) > 0 ) {
			$this->echoResponse( 422, $validationErrors );
		} else {
			$selectedProvider   = ProviderService::getProvider( $request['provider'] );
			$providerSettings   = ProviderService::getProviderSettings( $request['provider'] );
			$providerController = $selectedProvider::getController();
			if ( ! $providerController instanceof IssuerInterface ) {
				$this->echoResponse( 409, [ "The selected provider can't issue credentials!" ] );
			}
			try {
				$args = array_merge( $providerSettings, $request );
				// payload is CredentialResponse
				$payload = $providerController->createCredential( $args );
				$this->echoResponse( 200, $payload->asArray() );
			} catch ( Exception $e ) {
				$this->echoResponse( 500, [ $e->getMessage() ] );
			}
		}
	}
}
