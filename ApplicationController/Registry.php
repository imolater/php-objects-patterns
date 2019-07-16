<?

namespace ApplicationController\Registry;

use ApplicationController\ApplicationController;
use ApplicationController\Command\Command;
use ApplicationController\ControllerMap;
use ApplicationController\Request;

abstract class Registry {
    abstract protected function get( $key );

    abstract protected function set( $key, $value );
}

class RequestRegistry extends Registry {
    private static $instance = null;
    private $storage = array();

    private function __construct() {
    }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $key
     *
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
    static function getAppController(): ApplicationController {
        $instance = self::instance();

        if ( is_null( $instance->appController ) ) {
            $map = self::getControllerMap();
            $instance->appController = new ApplicationController( $map );
        }

        return $instance->appController;
    }
}

class ApplicationHelper {
    private static $instance = null;
    private $config = 'config.xml';

    private function __construct() {
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
            $this->ensure( file_exists( $this->config ), 'Файла конфигурации не найден!' );

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

class ApplicationException extends \Exception {
}