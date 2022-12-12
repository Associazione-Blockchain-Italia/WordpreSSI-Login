<?php

namespace Inc\Base;

use Inc\Controllers\ConnectionsGetController;
use Inc\Controllers\CredentialCreateController;
use Inc\Controllers\CredentialGetController;
use Inc\Controllers\ProviderConfigurationTestController;
use Inc\Controllers\UsersGetController;
use Inc\Controllers\VerificationCreateController;
use Inc\Controllers\VerificationGetController;
use Inc\Providers\Trinsic\Controllers\Impl\Messaging\TrinsicChatController;

/**
 * This class maps an ajax action to a given controller
 */
class Router
{

    /**
     *
     * @return void
     */
    public function register()
    {
        add_action('wp_ajax_nopriv_createCredential', array(new CredentialCreateController(), 'handle'));
        add_action('wp_ajax_nopriv_getCredential', array(new CredentialGetController(), 'handle'));
        add_action('wp_ajax_nopriv_verifyCredential', array(new VerificationCreateController(), 'handle'));
        add_action('wp_ajax_nopriv_getVerification', array(new VerificationGetController(), 'handle'));
        add_action('wp_ajax_nopriv_getRegisteredUsers', array(new UsersGetController(), 'handle'));
        add_action('wp_ajax_nopriv_getConnectedUsers', array(new ConnectionsGetController(), 'handle'));
        add_action('wp_ajax_nopriv_providerConfigurationTest', array(new ProviderConfigurationTestController(), 'handle'));
	    add_action('wp_ajax_nopriv_trinsicSendMessage', array(new TrinsicChatController(), 'sendMessage'));
		add_action('wp_ajax_createCredential', array(new CredentialCreateController(), 'handle'));
        add_action('wp_ajax_getCredential', array(new CredentialGetController(), 'handle'));
        add_action('wp_ajax_verifyCredential', array(new VerificationCreateController(), 'handle'));
        add_action('wp_ajax_getVerification', array(new VerificationGetController(), 'handle'));
        add_action('wp_ajax_getRegisteredUsers', array(new UsersGetController(), 'handle'));
        add_action('wp_ajax_getConnectedUsers', array(new ConnectionsGetController(), 'handle'));
		add_action('wp_ajax_providerConfigurationTest', array(new ProviderConfigurationTestController(), 'handle'));
	    add_action('wp_ajax_trinsicSendMessage', array(new TrinsicChatController(), 'sendMessage'));
	}

}
