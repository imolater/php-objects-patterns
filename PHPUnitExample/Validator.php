<?php

namespace PHPUnitExample;

class Validator {
    private $store;

    public function __construct( UserStore $store) {
        $this->store = $store;
    }

    function validateUser( $mail, $pass ) {
        $user = $this->store->getUser($mail);

        if (! is_array($user)) {
            return false;
        }

        if ($user['pass'] != $pass) {
            $this->store->notifyPasswordFailure($mail);
            return false;
        }

        return true;
    }
}