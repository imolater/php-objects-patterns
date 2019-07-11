<?
/**
 * Шаблон Page Controller
 */

namespace PageController;

use Registry\ApplicationRegistry;

abstract class Controller {
    abstract function process();

    public function forward( $resource, $type = null ) {
        if ( is_null( $type ) ) {
            $file = "{$resource}.php";
            include( "$file" );

            /** @var Controller $object */
            $class = "PageController\\" . $resource;
            $object = new $class;
            $object->process();
        } else {
            $sep = DIRECTORY_SEPARATOR;
            $file = "{$type}{$sep}{$resource}.php";
            $request = $this->getRequest();
            include( "$file" );
            exit( 0 );
        }
    }

    public function getRequest() {
        return ApplicationRegistry::getRequest();
    }
}

/* Тесты
    $controller = new PageController\AddVenue();
    $controller->process();
*/