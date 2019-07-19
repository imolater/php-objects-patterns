<?php

namespace ApplicationExample\Util;

require_once 'ApplicationExample/Persist/UserStore.php';

use ApplicationExample\Persist\UserStore;

class Validator {
    private $store;

    public function __construct( UserStore $store ) {
        $this->store = $store;
    }

    function validateUser( $mail, $pass ) {
        $user = $this->store->getUser( $mail );

        if ( is_null( $user ) ) {
            return false;
        }

        if ( $user->getPass() != $pass ) {
            $this->store->notifyPasswordFailure( $mail );
            return false;
        }

        return true;
    }
}