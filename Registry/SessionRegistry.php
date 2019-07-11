<?php
/**
 * Шаблон Registry
 */

namespace Registry;

class SessionRegistry extends Registry {
    private static $instance = null;

    private function __construct() {
        session_start();
    }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function get( $key ) {
        if ( isset( $_SESSION[ __CLASS__ ][ $key ] ) ) {
            return $_SESSION[ __CLASS__ ][ $key ];
        }

        return null;
    }

    protected function set( $key, $value ) {
        $_SESSION[ __CLASS__ ][ $key ] = $value;
    }

    static function setDSN( $dsn ) {
        self::instance()->set( 'dsn', $dsn );
    }

    static function getDsn() {
        return self::instance()->get( 'dsn' );
    }
}