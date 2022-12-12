<?php

namespace Inc\Providers\Trinsic\Validators;

use Inc\Contracts\ProviderSettingsValidatorInterface;

class TrinsicSettingsValidator implements ProviderSettingsValidatorInterface {

    static function validateSettings(array $fields, array $oldValues, array $newValues) : array {
        $validationErrors = [];
        if(empty($newValues['credentialsMapping.role']) || empty($newValues['credentialsMapping.identifier'])){
            $validationErrors['credentialsMapping.role'] = __('Error: credential mapping incorrect!');
        }
        return $validationErrors;
    }

}
