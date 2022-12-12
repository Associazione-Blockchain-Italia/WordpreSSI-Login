<?php

namespace Inc\Controllers;

use Exception;
use Inc\Responses\BaseResponse;
use Inc\Services\ProviderService;
use Respect\Validation\Rules;

/**
 * The controller allows to check the configuration of a given provider.
 */
class ProviderConfigurationTestController extends Controller {

    /**
     * The method executes the configuration check for the provider specified in the request body.
     *
     * @return void
     */
    public function handle()
    {
        $request = [
            'provider' => $_POST['provider'],
	        'args'=>$_POST['args']
        ];

        $rules = [
            'provider' => new Rules\AllOf(new Rules\NotBlank(), new Rules\In(array_keys(ProviderService::getProviders()))),
	        'args'=>new Rules\AllOf()
        ];

		$validationErrors = $this->validateRequest( $request, $rules );
		if ( $validationErrors && sizeof( $validationErrors ) > 0 ) {
			$this->echoResponse( 422, $validationErrors );
		} else {
			try {
                $selectedProvider = ProviderService::getProvider($request['provider']);
                $providerSettings = ProviderService::getProviderSettings($request['provider']);
                $providerConfigurationTester = $selectedProvider::getProviderConfigurationTester();
				$args = array_merge($providerSettings, $request);
                $configurationErrors = $providerConfigurationTester->checkSettings($args);
                $response = new BaseResponse($request["provider"]);
                $response->setArgs($configurationErrors);
                $this->echoResponse(200, $response->asArray());
            } catch ( Exception $e ) {
				$this->echoResponse( 500, $e->getMessage() );
			}
		}
	}

}
