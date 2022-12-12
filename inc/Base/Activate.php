<?php
namespace Inc\Base;

/**
 * The class is used to initialize the plugin
 */
class Activate{

    /**
     * The list of actions to execute when the plugin is activated
     * @return void
     */
    public static function activate(){
        flush_rewrite_rules();
    }
}
