<?php

namespace Inc\Services;

use Inc\Exceptions\InternalServerErrorException;

/**
 * This class is used to abstract operations on the users
 */
class UsersService
{

    /**
     * Create a Wordpress user
     *
     * @param      $identifier
     * @param      $role
     * @param      $provider
     * @param null $credentialId
     *
     * @return int
     * @throws InternalServerErrorException
     */

    public static function createUser($identifier, $role, $provider, $credentialId = null): int
    {
        $credentials = [
            'user_login' => $identifier,
            'user_pass' => self::generatePassword(),
            'user_email' => $identifier . '@ssi.it',
            'role' => $role
        ];
        $resp = wp_insert_user($credentials);
        if (is_wp_error($resp)) {
            throw new InternalServerErrorException($resp->errors);
        }
        update_user_meta($resp, 'ssi', true);
        update_user_meta($resp, 'provider', $provider);
        update_user_meta($resp, 'credentialId', $credentialId);

        return $resp;
    }

    /**
     * Authenticate the user given its identifier
     *
     * @param $identifier
     */
    public static function authenticateUser($identifier): void
    {
        $password = self::generatePassword();

        $user = get_user_by('login', $identifier);

        wp_set_password($password, $user->ID);
        wp_signon(
            [
                'user_login' => $identifier,
                'user_password' => $password,
                'remember' => true
            ]
        );
    }

    /**
     * @return false|string
     */
    private static function generatePassword()
    {
        return substr(str_shuffle(MD5(microtime())), 0, 10);
    }

}
