<?php

namespace Inc\Pages;

use Inc\Contracts\ProviderInterface;
use Inc\Fields\CheckboxField;
use Inc\Fields\Field;
use Inc\Helpers\PluginPathHelper;
use Inc\Sections\Section;

/**
 * This class has the responsibility to build the Admin Page.
 */
class AdminPage extends Page
{

    /**
     * @inheritDoc
     */
    public function __construct($providers)
    {
        parent::__construct("ssi_plugin_settings", "SSIPlugin", "SSIPlugin");
        $this->setupPluginGlobalSettings($providers);
        $this->setupSubpages($providers);
        $this->setScripts(
            [
                'adminPluginSettingsPageScript' => 'SSIPlugin/assets/admin/pluginSettings/plugin-settings-page.js'
            ]
        );
        $this->setStyles(
            [
                'adminPluginSettingsPageStyle' => 'SSIPlugin/assets/admin/pluginSettings/plugin-settings-page.css'
            ]
        );
    }

    /**
     * Set the global settings of the provider.
     *
     * @param $providers
     *
     * @return void
     */
    private function setupPluginGlobalSettings($providers)
    {
        $active_providers = new Section('active_providers', "Active Providers");
        /** @var ProviderInterface $provider */
        foreach ($providers as $provider) {
            $active_providers->addField(new CheckboxField($provider::getName(), $provider::getId(), $this->getId(), "active_providers"));
        }
        $this->addSection($active_providers);
    }

    /**
     * Setup the providers settings pages and register their sections.
     *
     * @param $providers
     *
     * @return void
     */
    private function setupSubpages($providers)
    {
        /** @var ProviderInterface $provider */
        foreach ($providers as $provider) {
            $providerPage = new ProviderSettingsPage($provider::getId(), $provider::getName(), $provider::getName());
            $providerSection = new Section($provider::getId(), $provider::getName());
            /** @var Field $field */
            foreach ($provider::getFields() as $field) {
                $providerSection->addField($field);
            }
            $providerPage->addSection($providerSection);
            $this->addSupbage($providerPage);
        }
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $pieces = [PluginPathHelper::getPluginViewsFolderPath(), "Admin", "Dashboard", "dashboard.php"];
        $filePath = PluginPathHelper::pathFromPieces($pieces);
        if (PluginPathHelper::fileExists($filePath)) {
            include $filePath;
        }
    }

}
