<?
/**
 * Шаблон Front Controller
 */

namespace FrontController;

use FrontController\Command\CommandResolver;

class Controller {
    private function __construct() { }

    /**
     * @throws \ReflectionException
     */
    static function run() {
        /* Получаем Singleton-сущность контроллера */
        $instance = new Controller();
        /* Инициализируем настройки */
        $instance->init();
        /* Работаем с запросом */
        $instance->handleRequest();
    }

    private function init() {
        $appHelper = Registry\ApplicationHelper::instance();
        $appHelper->init();
    }

    /**
     * @throws \ReflectionException
     */
    private function handleRequest() {
        $request = \Registry\ApplicationRegistry::getRequest();
        $resolver = new CommandResolver();
        $cmd = $resolver->getCommand( $request );
        $cmd->execute( $request );
    }
}

/* Тесты
// Имитация запроса с данными
$request = Registry\ApplicationRegistry::getRequest();
$request->setProperty('action', 'login');
// Активация контроллера
FrontController\Controller::run();
*/
