<?php

namespace Observer;

class Login implements \SplSubject {
    private $storage;
    private $status = array();

    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;


    public function __construct() {
        $this->storage = new \SplObjectStorage();
    }

    public function handleLogin( $user, $ip ) {
        $isValid = false;

        switch ( rand( 1, 3 ) ) {
            case 1:
                $this->setStatus(self::LOGIN_ACCESS, $user, $ip );
                $isValid = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip );
                $isValid = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip );
                $isValid = false;
                break;
        }

        return $isValid;
    }

    private function setStatus( $status, $user, $ip ) {
        $this->status = array( $status, $user, $ip );
    }

    public function attach( \SplObserver $observer ) {
        if ( $observer instanceof LoginObserver ) {
            $this->storage->attach( $observer );
        } else {
            print "Непподерживаемый тип объекта!";
        }
    }

    public function detach( \SplObserver $observer ) {
        if ( $observer instanceof LoginObserver ) {
            $this->storage->detach( $observer );
        } else {
            print "Непподерживаемый тип объекта!";
        }
    }

    public function notify() {
        foreach ( $this->storage as $observer ) {
            $observer->update( $this );
        }
    }
}