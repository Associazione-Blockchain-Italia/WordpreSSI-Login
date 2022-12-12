<?php

namespace Inc\Providers\ExampleProvider\Validators;

use Inc\Contracts\ProviderSettingsValidatorInterface;
use Inc\Exceptions\NotImplementedException;

class ExampleProviderSettingsValidator implements ProviderSettingsValidatorInterface {

    static function validateSettings(array $fields, array $oldValues, array $newValues) : array {
        /*
         * This function is used to validate the settings specified for a given provider.
         *
         * This ensure that all the required fields, for example, are filled and so on.
         *
         * Ideally a message for each field is returned but you can also return an array with the first
         * error with the invalid field and an explanation.
         *
         */
        throw new NotImplementedException();
    }

}
