<div class="wrap">
    <h1><?php echo $this->getPageTitle() ?></h1>
    <?php settings_errors() ?>
    <div>
        <button
                id="provider-configuration-test-button"
                type="button"
                class="button page-title-action"
                onclick="providerConfigurationTest('<?php echo $this->getId() ?>')"
                >Check Configuration
        </button>
    </div>
    <div id="provider-configuration-test-results-div"
         class="notice is-dismissible"
         style="display: none"
    >
        <p><strong>Error: credential mapping incorrect!</strong></p>
    </div>
    <form id="provider-settings-form" method="post" action="options.php">
        <?php
        settings_fields($this->getId());
        do_settings_sections($this->getId());
        submit_button();
        ?>
    </form>

</div>
