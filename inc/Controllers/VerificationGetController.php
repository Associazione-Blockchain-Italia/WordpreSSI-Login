<?php

namespace Inc\Controllers;

use Exception;
use Inc\Contracts\VerifierInterface;
use Inc\Services\CredentialStatusService;
use Inc\Services\ProviderService;
use Inc\Services\UsersService;
use Respect\Validation\Rules;

class VerificationGetController extends Controller {

    public function handle()
    {
        $request = [
            'provider' => $_POST['provider'],
            'verificationId' => $_POST['verificationId'],
	        'args'=>$_POST['args']
        ];

        $rules = [
            'provider' => new Rules\AllOf(new Rules\NotBlank(), new Rules\In(ProviderService::getActiveProviders())),
            'verificationId' => new Rules\AllOf(new Rules\NotBlank()),
	        'args'=>new Rules\AllOf()
        ];

		$validationErrors = $this->validateRequest( $request, $rules );
		if ( $validationErrors && sizeof( $validationErrors ) > 0 ) {
			$this->echoResponse( 422, $validationErrors );
		} else {
			try {
                $selectedProvider = ProviderService::getProvider($request['provider']);
                $providerSettings = ProviderService::getProviderSettings($request['provider']);
                $providerController = $selectedProvider::getController();
                if(!$providerController instanceof VerifierInterface){
                    $this->echoResponse(409, ["The selected provider can't verify credentials!"]);
                }
				$args=array_merge($providerSettings, $request);
                $payload = $providerController->getVerification($args);
                if ($payload->getState() === CredentialStatusService::ACCEPTED) {
                    UsersService::authenticateUser($payload->getIdentifier());
                }
                $this->echoResponse(200, $payload->asArray());
            } catch ( Exception $e ) {
				$this->echoResponse( 500, $e->getMessage() );
			}
		}
	}

}
