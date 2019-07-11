<?php
/**
 * Шаблон Front Controller
 */

namespace FrontController;

class Request {
    private $storage = array();
    private $messages = array();

    public function __construct() {
        $this->init();
    }

    public function init() {
        if ( isset( $_REQUEST ) ) {
            $this->storage = $_REQUEST;
            return;
        }

        foreach ( $_SERVER[ 'argv' ] as $arg ) {
            if ( strpos( $arg, '=' ) ) {
                list( $key, $value ) = explode( '=', $arg );
                $this->setProperty( $key, $value );
            }
        }

        $this->setProperty('action', 'login');
    }

    public function getProperty( $key ) {
        if ( isset( $this->storage[ $key ] ) ) {
            return $this->storage[ $key ];
        }

        return null;
    }

    public function setProperty( $key, $value ) {
        $this->storage[ $key ] = $value;
    }

    public function addMessage( $mess ) {
        $this->messages[] = $mess;
    }

    /**
     * @return array
     */
    public function getMessages(): array {
        return $this->messages;
    }
}