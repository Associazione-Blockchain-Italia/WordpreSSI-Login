<?php

namespace Inc\Contracts;

use Inc\Fields\Field;

/**
 * The interface exposes the methods used to validate the settings/configuration of a provider.
 */
interface ProviderSettingsValidatorInterface
{

    /**
     * Validates the configuration of a provider.
     * The function takes the fields defined for a provider, the current values saved for each field ($oldValues)
     * and the values that the user wants to save ($newValues)
     * After the validation, if any error is present is returned in a dictionary where each key is the field id and
     * each value is the error associated for that field.
     *
     * @param $fields    Field[] : the list of fields defined for the provider
     * @param $oldValues array : the associative array containing the previously saved values
     * @param $newValues array : the associative array containing the new values to validate
     *
     * @return array
     */
    public static function validateSettings(array $fields, array $oldValues, array $newValues): array;

}
