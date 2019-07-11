<?php
/**
 * Шаблон Registry
 */

namespace Registry;

class RequestRegistry extends Registry {
    private static $instance = null;
    private $storage = array();

    private function __construct() { }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get( $key ) {
        if ( isset( $this->storage[ $key ] ) ) {
            return $this->storage[ $key ];
        }

        return null;
    }

    public function set( $key, $value ) {
        $this->storage[ $key ] = $value;
    }

    /**
     * @return Request
     */
    static function getRequest(): Request {
        $instance = self::instance();

        if ( is_null( $instance->get( 'request' ) ) ) {
            $instance->set( 'request', new Request() );
        }

        return $instance->get( 'request' );
    }
}