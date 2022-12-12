<?php

namespace Inc;

/**
 * Entry Point of the System
 */
final class Init
{

    /**
     * List of services to initialize.
     *
     * @return array Full list of classes
     */
    static function getServices(): array
    {
        return [
            Base\SettingsLinks::class,
            Base\Menu::class,
            Base\Login::class,
            Base\Router::class,
            Base\Shortcode::class,
            Base\UserTable::class
        ];
    }

	/**
	 * Loop through the classes, initialize them, and call the register() method if it exists
	 *
	 * @return void
	 */
	static function registerServices() {
		foreach ( self::getServices() as $class ) {
			$service = new $class;
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

}
