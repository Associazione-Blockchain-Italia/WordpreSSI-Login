<?php

namespace Inc\Base;

use Inc\Services\ProviderService;

/**
 * The class contains functions and definitions used to load the "login.php" page
 */
class Login {

    /**
     * The function is fired when the component is registered
     *
     * @return void
     */
	public function register() {
		add_action( 'login_message', array( $this, 'ssiloginMessage' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'my_enqueue' ) );

	}

    /**
     * The function is used to enqueue the scripts and styles used on the login page.
     *
     * @return void
     */
	function my_enqueue() {
		wp_enqueue_script( 'ajax-script', plugins_url( 'SSIPlugin/assets/script.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'animationwplogin_qrcode', plugins_url( 'SSIPlugin/assets/login/libs/qrious.min.js' ));
		wp_enqueue_script( 'animationwplogin', plugins_url( 'SSIPlugin/assets/animations.js' ), array( 'jquery' ) );
		wp_enqueue_style( 'ssilogin', plugins_url( 'SSIPlugin/assets/style.css' ) );
		wp_localize_script( 'ajax-script', 'ajax_object', array(
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'home_url'  => home_url(),
            'providers' => ProviderService::getProvidersAsJson(ProviderService::getActiveProviders())
		) );
	}

    /**
     * The function is used to print the link to login with ssi
     * @return void
     */
	public function ssiloginMessage() {
		echo
        '<div id="ssilogin" style="text-align: center">
             <div 
                id="ssishowproviders" 
                style="text-decoration: none; color: #50575e; cursor: pointer" 
                onclick="showProviders()"
                >
              Click here for the SSI Authentication
             </div>
             <div id="qrcode-container" style="display: none"><canvas id="qrcode"></canvas></div>
             <div class="loading-ring"></div>
        </div>';
	}
}
