<?php

namespace Inc\Base;

use Inc\Contracts\ConnectionInterface;
use Inc\Services\ProviderService;

class Shortcode
{
    public function register()
    {
        add_shortcode('ssiconnection', array($this, 'shortcodeConnection'));
    }

    public function shortcodeConnection($providedAttributes)
    {

        $attributes = shortcode_atts(['provider' => '', 'size' => 200], $providedAttributes);
        $provider = $attributes['provider'];

        if (!ProviderService::isProviderActive($provider)) {
            return "<p>Error: This provider isn't valid or active</p>";
        }

        $providerSettings = ProviderService::getProviderSettings($provider);
        $providerInst = ProviderService::getProvider($provider);
        $providerController = $providerInst::getController();
        if (!isset($providerSettings['shortcode']) || !$providerController instanceof ConnectionInterface) {
            return "<p>Error: shortcodes aren't available for this provider</p>";
        }

        if (!$providerSettings['invitationUrl']) {
            $connectionResponse = $providerController->createConnection($providerSettings);
            $arr = array_merge($providerSettings, ['invitationUrl'=> $connectionResponse->getConnectionInvitationUrl()]);
            update_option($provider, $arr);
        }

        $invitationUrl = $providerSettings['invitationUrl'];
        $size = $attributes['size'];
        return "<img id='qrcode' style='display: block; mix-blend-mode: multiply' src='{$this->getShortcodeURL($size, $invitationUrl)}'>";
    }

    private function getShortcodeURL($size, $invitationURL) : string {
        return "https://chart.googleapis.com/chart?cht=qr&chs={$size}x{$size}&chl={$invitationURL}";
    }
}
