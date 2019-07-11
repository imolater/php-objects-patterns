<?php
/**
 * Шаблон Registry
 */

namespace FrontController\Registry;

class ApplicationHelper {
    private static $instance = null;
    private $config = 'FrontController' . DIRECTORY_SEPARATOR . 'config.xml';

    private function __construct() { }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function init() {
        $dsn = ApplicationRegistry::getDSN();

        if ( is_null( $dsn ) ) {
            $this->getOptions();
        }

        return;
    }

    private function getOptions() {
        try {
            $this->ensure( file_exists( $this->config ), 'Файла конфигурации не найден!' );

            $options = simplexml_load_file( $this->config );
            $this->ensure( $options instanceof \SimpleXMLElement, 'Файл конфигурации испорчен!' );

            $dsn = (string)$options->dsn;
            $this->ensure( $dsn, 'DSN не найден' );

            ApplicationRegistry::setDSN( $dsn );
        } catch ( ApplicationException $e ) {
            print $e->getMessage();
        }
    }

    /**
     * @param $exp
     * @param $mess
     * @throws ApplicationException
     */
    private function ensure( $exp, $mess ) {
        if ( ! $exp ) {
            throw new ApplicationException( $mess );
        }
    }
}

class ApplicationException extends \Exception {}