<?php

namespace Inc\Fields;

use Inc\Helpers\PluginPathHelper;

/**
 * The class represent a configuration field saved as boolean (1 if true, 0 or not defined if false)
 */
class CheckboxField extends Field
{

    /**
     * The function echoes the field
     *
     * @return void
     */
    public function render()
    {
        $filename = PluginPathHelper::getPathForFile(["inc", "Templates", "Fields", "checkbox_field.php"]);
        if (PluginPathHelper::fileExists($filename)) {
            include $filename;
        }
    }

}
