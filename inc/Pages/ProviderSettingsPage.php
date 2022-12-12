<?php

namespace Inc\Pages;

use Inc\Helpers\PluginPathHelper;

/**
 * A provider settings page represent a specific provider page on the Admin area.
 * Eg. this is the class responsible for loading the Trinsic Settings Page in the admin area.
 */
class ProviderSettingsPage extends Page
{

    /**
     * @param $id
     * @param $pageTitle
     * @param $menuTitle
     */
    public function __construct($id, $pageTitle, $menuTitle) {
        parent::__construct($id, $pageTitle, $menuTitle);
        $this->setStyles(
            [
                'adminProviderSettingsPageStyle' => 'SSIPlugin/assets/admin/providerPage/provider-settings-page.css'
            ]
        );
        $this->setScripts(
            [
                'adminProviderSettingsPageScript' => 'SSIPlugin/assets/admin/providerPage/provider-settings-page.js'
            ]
        );
    }

    /**
     * @return mixed|void
     */
    public function render()
    {
        $pieces = [PluginPathHelper::getPluginViewsFolderPath(), "Admin", "ProviderSettings", "provider_settings.php"];
        $filePath = PluginPathHelper::pathFromPieces($pieces);
        if (PluginPathHelper::fileExists($filePath)) {
            include $filePath;
        }
    }

}
