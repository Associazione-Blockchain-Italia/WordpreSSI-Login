<?php

namespace Inc\Base;

/**
 * The class is used to customize the menu page "users"
 */
class UserTable
{

    /**
     * @return void
     */
    function register()
    {
        add_filter('manage_users_columns', array($this, 'new_modify_user_table'));

        add_filter('manage_users_custom_column', array($this, 'new_modify_user_table_row'), 10, 3);

        add_filter('bulk_actions-users', array($this, 'my_bulk_actions'));
    }

    /**
     * @param $column
     *
     * @return mixed
     */
    function new_modify_user_table($column)
    {
        $column['credentialId'] = 'Credential ID';
        $column['provider'] = 'Provider';

        return $column;
    }

    /**
     * @param $val
     * @param $column_name
     * @param $user_id
     *
     * @return mixed|string
     */
    function new_modify_user_table_row($val, $column_name, $user_id)
    {
        switch ($column_name) {
            case 'credentialId' :
                return get_user_meta($user_id)['credentialId'][0] ?? '';
            case 'provider' :
                return get_user_meta($user_id)['provider'][0] ?? '';
            default:
        }

        return $val;
    }

    /**
     * @param $bulk_array
     *
     * @return mixed
     */
    function my_bulk_actions($bulk_array)
    {
        $bulk_array['revoke'] = __('Revoke credential');

        return $bulk_array;
    }

}
