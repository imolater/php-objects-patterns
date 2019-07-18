<?
/**
 * Шаблон Decorator
 */

namespace Decorator;

abstract class Decorator extends ProcessRequest {
    protected $processRequest;

    public function __construct( ProcessRequest $request ) {
        $this->processRequest = $request;
    }
}

class LogRequestDecorator extends Decorator {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": регистрация запроса \n";
        $this->processRequest->process( $helper );
    }
}

class AuthenticateRequestDecorator extends Decorator {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": аутентификация запроса \n";
        $this->processRequest->process( $helper );
    }
}

class StructureRequestDecorator extends Decorator {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": структурирование запроса \n";
        $this->processRequest->process( $helper );
    }
}

/* Тесты
$test = new Decorator\AuthenticateRequestDecorator(
    new Decorator\StructureRequestDecorator(
        new Decorator\LogRequestDecorator(
            new Decorator\MainProcessRequest()
        )
    ) );

$test->process( new Decorator\RequestHelper() );
*/