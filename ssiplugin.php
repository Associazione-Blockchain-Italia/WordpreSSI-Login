<?php
/**
 * @package SSIPlugin
 * @version 0.0.1
 * Plugin Name: SSI Plugin
 * Plugin URI:
 * Description: SSI Plugin for WordPress
 * Author: Araneum
 * Version: 0.0.1
 * Author URI:
 * Text Domain: ssiplugin
 */

//If the file is called directly, abort
defined('ABSPATH') or die();


//Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_ssiplugin()
{
    \Inc\Base\Activate::activate();
}

/**
 * The code that runs during plugin deactivation
 */
function deactivate_ssiplugin()
{
    \Inc\Base\Deactivate::deactivate();
}

//Procedural way
register_activation_hook(__FILE__, 'activate_ssiplugin');
register_deactivation_hook(__FILE__, 'deactivate_ssiplugin');

/**
 * Initialize all the core classes of the SSI plugin
 */
if (class_exists('Inc\\Init')) {
    Inc\Init::registerServices();
}

