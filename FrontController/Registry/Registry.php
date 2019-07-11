<?
/**
 * Шаблон Registry
 */

namespace FrontController\Registry;

abstract class Registry {
    abstract protected function get( $key );

    abstract protected function set( $key, $value );
}