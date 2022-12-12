<?php

namespace Inc\Controllers;

/**
 * The controller handles the get request to the users endpoint
 */
class UsersGetController extends Controller
{

    /**
     * The function return a list of all the users registered with ssi
     *
     * @return void
     */
    public function handle()
    {
        $utenti = [];
        $users = get_users(array('meta_key' => 'ssi'));
        foreach ($users as $user) {
            $utenti[] = ['userId'=>$user->ID, 'credentialId'=> get_user_meta($user->ID, 'credentialId', true)];
        }
        $this->echoResponse(200, $utenti);
    }
}
