<?php

namespace Inc\Controllers;

use Exception;
use Inc\Contracts\IssuerInterface;
use Inc\Services\CredentialStatusService;
use Inc\Services\ProviderService;
use Inc\Services\UsersService;
use Respect\Validation\Rules;

/**
 * This controller handles the requests to check the status of a credential.
 *
 */
class CredentialGetController extends Controller
{

    /**
     * Check the status of the 'connection create request'.
     * A request of this type must contain the provider that issued the credential, the credentialId, the identifier ad the role.
     * The controller accept also other parameters under the key "args".
     * This is useful in order to pass additional data to the provider.
     *
     * After the request validation, the provider specified is resolved and if is an issuer the provider method is used
     * to handle the request.
     *
     */
    public function handle()
    {
        $request = [
            'provider' => $_POST['provider'],
            'credentialId' => $_POST['credentialId'],
            'identifier' => $_POST['identifier'],
            'role' => $_POST['role'],
	        'args' => $_POST['args']
        ];
        $rules = [
            'provider' => new Rules\AllOf(new Rules\NotBlank(), new Rules\In(ProviderService::getActiveProviders())),
            'identifier' => new Rules\AllOf(new Rules\NotBlank()),
            'role' => new Rules\AllOf(new Rules\NotBlank()),
            'credentialId' => new Rules\AllOf(new Rules\NotBlank()),
            'args' => new Rules\AllOf( ),
        ];
        $validationErrors = $this->validateRequest($request, $rules);
        if ($validationErrors && sizeof($validationErrors) > 0) {
            $this->echoResponse(422, $validationErrors);
        } else {
            $selectedProvider = ProviderService::getProvider($request['provider']);
            $providerSettings = ProviderService::getProviderSettings($request['provider']);
            $providerController = $selectedProvider::getController();
            if(!$providerController instanceof IssuerInterface){
                $this->echoResponse(409, ["The selected provider can't issue credentials!"]);
            }
            try {
                $identifier = $request['identifier'];
                $role = $request['role'];
                $credentialId = $request['credentialId'];
                $args = array_merge($providerSettings, $request);
                $payload = $providerController->getCredential($args);
                if ($payload->getState() === CredentialStatusService::ISSUED) {
                   UsersService::createUser($identifier, $role, $selectedProvider->getName(), $credentialId);
                }
                $this->echoResponse(200, $payload->asArray());
            } catch (Exception $e) {
                $this->echoResponse(500, $e->getMessage());
            }
        }
    }

}
