<?php

namespace Inc\Base;

use Inc\Fields\Field;
use Inc\Pages\AdminPage;
use Inc\Pages\Page;
use Inc\Sections\Section;
use Inc\Services\ProviderService;
use Inc\Helpers\PluginPathHelper;

/**
 * This class is used to build the menu pages for the plugin
 *
 */
class Menu
{

    private array $hookToPage = [];

    /**
     * The function is used to register the component and builds the menu pages for the plugin
     *
     * @return void
     */
    public function register(): void {
        $adminPage = new AdminPage(ProviderService::getProviders());

        add_action('admin_init', function () use ($adminPage) {
            $this->registerPage($adminPage);
        });

        add_action('admin_menu', function () use ($adminPage) {
            $hook = add_menu_page(
                $adminPage->getPageTitle(),
                $adminPage->getMenuTitle(),
                'manage_options',
                $adminPage->getId(),
                array($adminPage, "render"),
                'dashicons-id',
                70
            );
            $this->hookToPage[$hook] = $adminPage;
            foreach ($adminPage->getSubpages() as $page) {
                $hook = add_submenu_page(
                    $adminPage->getId(),
                    $page->getPageTitle(),
                    $page->getMenuTitle(),
                    'manage_options',
                    $page->getId(),
                    array($page, "render"),
                );
                $this->hookToPage[$hook] = $page;
            }
			add_submenu_page(
				$adminPage->getId(),
				"Trinsic Chat",
				"Trinsic Chat",
				"manage_options",
				"trinsic_chat",
				array($this, "renderTrinsicChat")
			);
        });

        add_action('admin_enqueue_scripts', function ($hook) {
            foreach ($this->hookToPage as $key => $page) {
                wp_enqueue_script(
                    "adminGlobalScript",
                    plugins_url('SSIPlugin/assets/admin/globals/admin-globals.js'),
                    array('jquery')
                );
                wp_localize_script("adminGlobalScript", 'ajax_object', array(
                    'ajax_url'  => admin_url( 'admin-ajax.php' ),
                    'home_url'  => home_url(),
                ));
                wp_enqueue_style(
                    "adminGlobalStyle",
                    plugins_url('SSIPlugin/assets/admin/globals/admin-globals.css'),
                );
                if ($hook === $key) {
                    foreach ($page->getScripts() as $scriptName => $scriptPath) {
                        wp_enqueue_script($scriptName, plugins_url($scriptPath), array('jquery'));
                        wp_localize_script($scriptName, 'ajax_object', array(
                            'ajax_url'  => admin_url( 'admin-ajax.php' ),
                            'home_url'  => home_url(),
                        ));
                    }
                    foreach ($page->getStyles() as $styleName => $stylePath) {
                        wp_enqueue_style($styleName, plugins_url($stylePath));
                    }
                }
            }
        });
    }

    /**
     * @param Page $page
     *
     * @return void
     */
    private function registerSections(Page $page)
    {
        /** @var Section $section */
        foreach ($page->getSections() as $section) {
            add_settings_section($section->getId(), $section->getTitle(), "", $page->getId());
            /** @var Field $field */
            foreach ($section->getFields() as $field) {
                add_settings_field($field->getId(), $field->getLabel(), array($field, 'render'), $page->getId(), $section->getId());
            }
        }
    }

    /**
     * @param Page $page
     *
     * @return void
     */
    private function registerPage( Page $page ) {
        $this->registerSections( $page );
        register_setting( $page->getId(),$page->getId());
        foreach ( $page->getSubpages() as $subpage ) {
            $providerId = $subpage->getId();
            register_setting(
                $subpage->getId(),
                $subpage->getId(),
                [
                    "sanitize_callback" => function ( $input ) use ( $providerId ) {
                        return $this->validate( $providerId, $input );
                    }
                ] );
            $this->registerPage( $subpage );
        }
    }


    /**
     * Validate the settings for a given provider.
     *
     * @param $providerId string : The provider id
     * @param $input      array : the fields to be saved with the provider settings
     *
     * @return array
     */
    function validate( string $providerId, array $input ): array {
        $selectedProvider = ProviderService::getProvider( $providerId );
        $oldValues        = ProviderService::getProviderSettings( $providerId );
        $providerFields   = $selectedProvider->getFields();
        $errorsDict       = $selectedProvider->getProviderSettingsValidator()->validateSettings( $providerFields, $oldValues, $input );
        if ( sizeof( array_keys( $errorsDict ) ) > 0 ) {
            foreach ( $errorsDict as $k => $m ) {
                add_settings_error( $selectedProvider->getId(), 'error', _( $m ), 'error' );
            }

            return $oldValues;
        }

        return $input;
    }

	function renderTrinsicChat(): void {
		require PluginPathHelper::getPluginPath() . "inc/Templates/TrinsicChat.php";
	}

}
