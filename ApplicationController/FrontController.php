<?

namespace {
    //Делаем нашу тестовую библиотеку одним из стандартных мест поиска
    $path = $_SERVER[ "DOCUMENT_ROOT" ] . "/test";
    set_include_path( get_include_path() . PATH_SEPARATOR . $path );

    // Подключаем нужные файлы
    require_once( "Request.php" );
    require_once( "Registry.php" );
    require_once( "Command.php" );
}

namespace ApplicationController {

    use ApplicationController\Command,
        ApplicationController\Registry;

    class FrontController {
        private function __construct() {
        }

        static function run() {
            // 1. Получаем Singleton контроллера
            $instance = new FrontController();
            // 2. Инициализируем настройки
            $instance->init();
            // 3. Работаем с запросом
            $instance->handleRequest();
        }

        private function init() {
            // 2.1 Получаем Singleton настроек приложения
            $appHelper = Registry\ApplicationHelper::instance();
            // 2.2 Инициализируем настройки
            $appHelper->init();
        }

        private function handleRequest() {
            // 3.1 Получаем Singleton-объект запроса. В нашем случае - это имитация входных данных
            $request = Registry\ApplicationRegistry::getRequest();

            // 3.2 Получаем объект контроллера приложения
            $controller = Registry\ApplicationRegistry::getAppController();

            // 3.3 Прогоняем коману по нашей "управляющей карте" Controller map
            //     через все переадрисации forward или же просто получаем 1 команду.
            //     В итоге в запросе будет записана финальная команда, для которой
            //     мы и подключим шаблон
            try {
                while ( $cmd = $controller->getCommand( $request ) ) {
                    /** @var Command\Command $cmd */
                    $cmd->execute( $request );
                }
            } catch ( ApplicationException $e ) {
                print $e->getMessage();
            }

            // 3.4 Подключаем шаблон для итоговой команды и передаем какие-то данные
            $this->invokeView( $controller->getView( $request ), $request->getTemplateData() );
        }

        private function invokeView( $file, $mess ) {
            include( "view/{$file}.php" );
            exit;
        }
    }

    class ApplicationController {
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
            $status = $prevCmd->getStatus();

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
            if ( isset( $this->invoked[ $cmdClass ] ) ) {
                throw new ApplicationException( 'Циклический вызов' );
            }

            $this->invoked[ $cmdClass ] = true;

            // Возвращаем командный объект
            return $cmdObj;
        }

        public function resolveCommand( $cmd ) {
            $class = $this->controllerMap->getClass( $cmd );
            $className = "ApplicationController\\Command\\" . $class;
            $dir = "command";
            $sep = DIRECTORY_SEPARATOR;

            $file = "{$dir}{$sep}{$class}.php";

            if ( file_exists( $file ) ) {
                require_once( "$file" );

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

            return null;
        }
    }

    class ControllerMap {
        private $viewMap = array();
        private $classMap = array();
        private $forwardMap = array();

        public function addClass( $cmd, $class ) {
            $this->classMap[ $cmd ] = $class;
        }

        public function getClass( $cmd ) {
            if ( isset( $this->classMap[ $cmd ] ) ) {
                return $this->classMap[ $cmd ];
            }

            return null;
        }

        public function addView( $view, $cmd = 'default', $status = 0 ) {
            $this->viewMap[ $cmd ][ $status ] = $view;
        }

        public function getView( $cmd, $status ) {
            if ( isset( $this->viewMap[ $cmd ][ $status ] ) ) {
                return $this->viewMap[ $cmd ][ $status ];
            }

            return null;
        }

        public function addForward( $cmd, $forward, $status = 0 ) {
            $this->forwardMap[ $cmd ][ $status ] = $forward;
        }

        public function getForward( $cmd, $status ) {
            if ( isset( $this->forwardMap[ $cmd ][ $status ] ) ) {
                return $this->forwardMap[ $cmd ][ $status ];
            }

            return null;
        }
    }

    class ApplicationException extends \Exception {
    }
}

namespace {
    ApplicationController\FrontController::run();
}