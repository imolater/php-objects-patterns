<?php

namespace PHPUnitExample;

class UserStore {
    private $users = array();

    function addUser( $name, $mail, $pass ) {
        if ( isset( $this->users[$mail] ) ) {
            throw new Exception( "Пользователь с email - {$mail} уже существует" );
        }

        if ( strlen( $pass ) < 5 ) {
            throw new Exception( "Длина пароля должна быть не менее 5 символов" );
        }

        $this->users[$mail] = array(
            'mail' => $mail,
            'name' => $name,
            'pass' => $pass
        );

        return true;
    }

    function notifyPasswordFailure( $mail ) {
        if ( isset( $this->users[$mail] ) ) {
            $this->users[$mail]['failed'] = time();
        }
    }

    public function getUser( $mail ) {
        return $this->users[$mail];
    }

}