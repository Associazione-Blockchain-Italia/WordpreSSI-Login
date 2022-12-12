<?php

namespace Inc\Controllers;

use Inc\Contracts\VerifierInterface;
use Inc\Services\ProviderService;
use Respect\Validation\Rules;

/**
 *
 */
class VerificationCreateController extends Controller {

    public function handle()
    {
        $request = [
            'provider' => $_POST['provider'],
	        'args' => $_POST['args']
        ];
        $rules = [
            'provider' => new Rules\AllOf(new Rules\NotBlank(), new Rules\In(ProviderService::getActiveProviders())),
	        'args' => new Rules\AllOf()
        ];
        $validationErrors = $this->validateRequest($request, $rules);
        if ($validationErrors && sizeof($validationErrors) > 0) {
            $this->echoResponse(422, $validationErrors);
        } else {
            $selectedProvider = ProviderService::getProvider($request['provider']);
            $providerSettings = ProviderService::getProviderSettings($request['provider']);
            $providerController = $selectedProvider::getController();
            if(!$providerController instanceof VerifierInterface){
                $this->echoResponse(409, ["The selected provider can't verify credentials!"]);
            }
            try {
				$args=array_merge($providerSettings,$request);
                $payload = $providerController->verifyCredential($args);
                $this->echoResponse(201, $payload->asArray());
            } catch (\Exception $e) {
                $this->echoResponse(500, $e->getMessage());
            }
        }
    }

}
