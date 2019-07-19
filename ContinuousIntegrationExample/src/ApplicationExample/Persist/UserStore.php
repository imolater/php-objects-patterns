<?php

namespace ApplicationExample\Persist;

require_once 'ApplicationExample/Domain/User.php';

use ApplicationExample\Domain\User;

/**
 * Class UserStore
 * @package ApplicationExample\Persist
 */
class UserStore {
    /**
     * @var array
     */
    private $users = array();

    /**
     * @param $name
     * @param $mail
     * @param $pass
     *
     * @return bool
     * @throws \Exception
     */
    function addUser( $name, $mail, $pass ) {
        if ( isset( $this->users[$mail] ) ) {
            throw new \Exception( "Пользователь с email - {$mail} уже существует" );
        }

        $this->users[$mail] = new User( $name, $mail, $pass );
        return true;
    }

    /**
     * @param $mail
     */
    function notifyPasswordFailure( $mail ) {
        if ( !is_null( $user = $this->getUser( $mail ) ) )
            $user->failed( time() );
    }

    /**
     * @param $mail
     *
     * @return User|null
     */
    public function getUser( $mail ): ?User {
        return $this->users[$mail];
    }
}