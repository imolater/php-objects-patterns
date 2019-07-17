<?php

namespace ApplicationController;

use ApplicationController\Exception\ApplicationException;

class Controller {
    private static $baseCmd = null;
    private static $defaultCmd = null;
    private $controllerMap;
    private $invoked = array();

    function __construct( ControllerMap $map ) {
        $this->controllerMap = $map;

        // Используем Reflection API, чтобы в дальнейшем делать анализ класса
        self::$baseCmd = new \ReflectionClass( "ApplicationController\Command\Command" );
        self::$defaultCmd = new Command\DefaultCommand();
    }

    function reset() {
        $this->invoked = array();
    }

    private function getResource( Request $request, $resource ) {
        $cmd = $request->getProperty( 'action' );
        $prevCmd = $request->getLastCommand();
        $status = ( !is_null( $prevCmd ) ) ? $prevCmd->getStatus() : 0;

        $method = "get{$resource}";

        // Ищем ресурс по принципу "от частного к общему"
        $resource = $this->controllerMap->$method( $cmd, $status );

        if ( is_null( $resource ) ) {
            $resource = $this->controllerMap->$method( $cmd, 0 );
        }

        if ( is_null( $resource ) ) {
            $resource = $this->controllerMap->$method( 'default', $status );
        }

        if ( is_null( $resource ) ) {
            $resource = $this->controllerMap->$method( 'default', 0 );
        }

        return $resource;
    }

    public function getView( Request $request ) {
        $view = $this->getResource( $request, 'View' );
        return $view;
    }

    // В случаее, если у команды есть перенаправление,
    // инициализируем его и, т.к. инциализация forward'а возвращает
    // имя новой команды, то записываем её в объект запроса
    private function getForward( Request $request ) {
        $forward = $this->getResource( $request, 'Forward' );

        if ( $forward ) {
            $request->setProperty( 'action', $forward );
        }

        return $forward;
    }

    public function getCommand( Request $request ) {
        $prevCmd = $request->getLastCommand();

        // Если нет предыдущей команды, то это первая команда в запросе
        if ( is_null( $prevCmd ) ) {
            // Получаем эту команду
            $cmd = $request->getProperty( 'action' );

            // Если её нет, то это стандартная команда
            if ( is_null( $cmd ) ) {
                // Пишем её в запрос и возвращаем её объект
                $request->setProperty( 'action', 'default' );
                return self::$defaultCmd;
            }
        } else {
            // Если есть предыдущая команда, то смотрим, переадресовывает ли
            // она нас куда-то
            $cmd = $this->getForward( $request );

            // Если такой нет, то это последняя команда в запросе
            if ( is_null( $cmd ) ) {
                return null;
            }
        }

        // Если в запросе было указана команда или у предыдущей команда была переадресация,
        // то определим, какому командному объекту она пренадлежит
        $cmdObj = $this->resolveCommand( $cmd );

        if ( is_null( $cmdObj ) ) {
            throw new ApplicationException( "Команда $cmd не найдена" );
        }

        // Предовращаем циклический вызов. Пока хз, что это за ситуация
        $cmdClass = get_class( $cmdObj );
        if ( isset( $this->invoked[$cmdClass] ) ) {
            throw new ApplicationException( 'Циклический вызов' );
        }

        $this->invoked[$cmdClass] = true;

        // Возвращаем командный объект
        return $cmdObj;
    }

    public function resolveCommand( $cmd ) {
        $class = $this->controllerMap->getClass( $cmd );

        if ( !is_null( $class ) ) {
            $sep = DIRECTORY_SEPARATOR;
            $dir = "ApplicationController{$sep}Command";

            $file = "{$dir}{$sep}{$class}.php";
            $file = stream_resolve_include_path($file);

            if ( file_exists( $file ) ) {
                require_once( "$file" );

                $className = "ApplicationController\\Command\\" . $class;

                if ( class_exists( $className ) ) {
                    // Используем Reflection API, чтобы узнать, является ли наш
                    // командный объект наследником базового класса
                    $cmdReflection = new \ReflectionClass( $className );

                    // Если да, то возвращаем его экземпляр
                    if ( $cmdReflection->isSubclassOf( self::$baseCmd ) ) {
                        return $cmdReflection->newInstance();
                    }
                }
            }
        }

        return null;
    }
}