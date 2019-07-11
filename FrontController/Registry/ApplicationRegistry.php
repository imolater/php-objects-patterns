<?php
/**
 * Шаблон Registry
 */

namespace FrontController\Registry;

class ApplicationRegistry extends Registry {
    private static $instance = null;
    private $dir = 'data';
    private $storage = array();
    private $timeMarks = array();
    private $request = null;

    private function __construct() {}

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function get( $key ) {
        $path = $this->dir . DIRECTORY_SEPARATOR . $key . '.txt';

        if ( file_exists( $path ) ) {
            clearstatcache();

            $timeMark = filemtime( $path );

            if ( ! isset( $this->timeMarks[ $key ] ) ) {
                $this->timeMarks[ $key ] = 0;
            }

            if ( $timeMark > $this->timeMarks[ $key ] ) {
                $data = file_get_contents( $path );
                $this->timeMarks[ $key ] = $timeMark;
                $this->storage[ $key ] = unserialize( $data );

                return $this->storage[ $key ];
            }
        }

        if ( isset( $this->storage[ $key ] ) ) {
            return $this->storage[ $key ];
        }

        return null;
    }

    protected function set( $key, $value ) {
        $this->storage[ $key ] = $value;
        $path = $this->dir . DIRECTORY_SEPARATOR . $key . '.txt';
        file_put_contents( $path, $value );
        $this->timeMarks[ $key ] = time();
    }

    static function getDSN() {
        return self::instance()->get( 'dsn' );
    }

    static function setDSN( $value ) {
        self::instance()->set( 'dsn', $value );
    }

    static function getRequest() {
        $instance = self::instance();

        if ( is_null( $instance->request ) ) {
            $instance->request = new \FrontController\Request();
        }

        return $instance->request;
    }
}