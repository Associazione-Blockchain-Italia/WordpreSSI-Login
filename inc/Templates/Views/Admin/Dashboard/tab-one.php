<div id="tab-1" class="tab-pane active">
    <?php
    settings_errors() ?>
    <form method="post" action="options.php">
        <?php
        settings_fields($this->getId());
        do_settings_sections($this->getId());
        submit_button();
        ?>
    </form>
</div>
