<?php

namespace ApplicationController\Registry;

use ApplicationController\Controller;
use ApplicationController\ControllerMap;
use ApplicationController\Request;

class ApplicationRegistry extends Registry {
    private static $instance = null;
    private $dir = 'data';
    private $storage = array();
    private $timeMarks = array();
    private $request = null;
    private $controllerMap = null;
    private $appController = null;

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

            if ( !isset( $this->timeMarks[ $key ] ) ) {
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

    static function getRequest() {
        $instance = self::instance();

        if ( is_null( $instance->request ) ) {
            $instance->request = new Request();
        }

        return $instance->request;
    }

    static function getControllerMap() {
        $instance = self::instance();

        if (isset($instance->controllerMap)) {
            return $instance->controllerMap;
        }

        return null;
    }

    static function setControllerMap( ControllerMap $map ) {
        $instance = self::instance();

        $instance->controllerMap = $map;
    }

    // Синглтон-подход установки/получения единственного экземпляра контроллера
    // приложения
    static function getAppController(): Controller {
        $instance = self::instance();

        if ( is_null( $instance->appController ) ) {
            $map = self::getControllerMap();
            $instance->appController = new Controller( $map );
        }

        return $instance->appController;
    }
}
