<?php
namespace Inc\Base;

/**
 * This class is used when the plugin is deactivated
 */
class Deactivate{

    /**
     * This method contains the list of actions that needs to be executed on plugin deactivation
     *
     * @return void
     */
    public static function deactivate(){
        flush_rewrite_rules();
    }

}
