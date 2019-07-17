<?php

namespace ApplicationController\Registry;

use ApplicationController\Command\Command;
use ApplicationController\ControllerMap;
use ApplicationController\Exception\ApplicationException;

class ApplicationHelper {
    private static $instance = null;
    private $config;

    private function __construct() {
        $path = 'ApplicationController' . DIRECTORY_SEPARATOR . 'config.xml';
        $this->config = stream_resolve_include_path($path);
    }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function init() {
        // 2.2.1 Берём настройки из кэша
        $map = ApplicationRegistry::getControllerMap();

        // 2.2.2 Если нет в кэше, формируем из файла настроек
        if ( is_null( $map ) ) {
            $this->getOptions();
        }

        return;
    }

    private function getOptions() {
        try {
            // Проверяем файл на сущестувование и корректность
            $this->ensure( file_exists( $this->config ), 'Файла конфигурации ' . $this->config . ' не найден!' );

            $options = simplexml_load_file( $this->config );
            $this->ensure( $options instanceof \SimpleXMLElement, 'Файл конфигурации испорчен!' );

            // Создаём экземпляр "карты" настроек
            $map = new ControllerMap();

            // Обрабатываем общие представления
            foreach ( $options->view as $view ) {
                $status = trim( $view[ 'status' ] );
                $value = (string)$view->value;

                if ( $status )
                    $status = Command::getStatusCode( $status );
                else
                    $status = 0;

                $map->addView( $value, 'default', $status );
            }

            // Обрабтываем представления для определенных команд
            foreach ( $options->command as $cmd ) {
                $cmdName = trim( $cmd[ 'name' ] );
                $view = (string)$cmd->view;
                $class = (string)$cmd->class;
                $status = $cmd->status;

                // Обрабатываем имена и псевдонимы команд
                if ( $class )
                    $map->addClass( $cmdName, $class );
                else
                    $map->addClass($cmdName, $cmdName);

                // Обрабатываем перенаправления команд
                if ( $status ) {
                    $code = trim( $status[ 'value' ] );
                    $code = Command::getStatusCode( $code );
                    $forward = (string)$status->forward;

                    $map->addForward($cmdName, $forward, $code);
                }

                $map->addView( $view, $cmdName );
            }

            // Сохраняем настройки в кэш
            ApplicationRegistry::setControllerMap( $map );

        } catch ( ApplicationException $e ) {
            print $e->getMessage();
        }
    }

    /**
     * @param $exp
     * @param $mess
     *
     * @throws ApplicationException
     */
    private function ensure( $exp, $mess ) {
        if ( !$exp ) {
            throw new ApplicationException( $mess );
        }
    }
}
