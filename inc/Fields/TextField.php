<?php

namespace Inc\Fields;

use Inc\Helpers\PluginPathHelper;

/**
 * A text field is a field used to save string values for a configuration
 */
class TextField extends Field
{

    /**
     * The function echoes the field
     *
     * @return void
     */
    public function render()
    {
        $filename = PluginPathHelper::getPathForFile(["inc", "Templates", "Fields", "text_field.php"]);
        if (PluginPathHelper::fileExists($filename)) {
            include $filename;
        }
    }

}
