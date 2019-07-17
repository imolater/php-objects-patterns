<?

namespace ApplicationController;

use ApplicationController\Command;
use ApplicationController\Exception\ApplicationException;

class FrontController {
    private function __construct() {}

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
    }
}