<?php

namespace Inc\Controllers;

use Inc\Services\ProviderService;

/**
 * The controller is used to handle the request of a shortcode
 */
class ConnectionsGetController extends Controller
{
    public function handle()
    {
        $activeProviders = ProviderService::getActiveProviders();
        $payload = [];
        foreach ($activeProviders as $provider) {
            $providerSettings = ProviderService::getProviderSettings($provider);
            if (isset($providerSettings['shortcode'])) {
                $providerInst = ProviderService::getProvider($provider);
                $providerController = $providerInst::getController();
                try{
                    $response = $providerController->getConnections($providerSettings,'Connected');
                    foreach ($response->getAllConnections() as $connectionId) {
                        $payload[] = [
                            'connectionId' => $connectionId,
                            'providerId' => $provider,
                            'providerName' => ProviderService::getProviders()[$provider]::getName()];
                    }
                }
                catch (\Exception $e){
                    $this->echoResponse(500, $e->getMessage());
                }
            }
        }
        $this->echoResponse(200, $payload);
    }
}
