<?php

namespace Inc\Base;

/**
 * The function is used to display the links for the Wordpressi Plugin
 */
class SettingsLinks
{

    /**
     * @return void
     */
    public function register()
    {
        add_filter('plugin_action_links_'.plugin_basename(dirname(__FILE__, 3)).'/ssiplugin.php', array($this, 'settings_link'));
    }

    /**
     * @param $links
     *
     * @return mixed
     */
    public function settings_link($links)
    {
        $settingLinks = '<a href="admin.php?page=ssi_plugin_settings">Impostazioni</a>';
        $links[] = $settingLinks;
        return $links;
    }
}
