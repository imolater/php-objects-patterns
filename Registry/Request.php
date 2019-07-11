<?php
/**
 * Шаблон Registry
 */

namespace Registry;

class Request {
    private $storage = array();
    private $messages = array();

    public function __construct() {
        $this->init();
    }

    public function init() {
        if ( isset( $_SESSION[ 'REQUEST_METHOD' ] ) ) {
            $this->storage = $_REQUEST;
            return;
        }

        foreach ( $_REQUEST as $key => $value ) {
            $this->setProperty( $key, $value );
        }
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