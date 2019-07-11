<?
/**
 * Шаблон Decorator
 */

namespace Decorator;

class RequestHelper {
}

abstract class ProcessRequest {
    abstract function process( RequestHelper $helper );
}

class MainProcess extends ProcessRequest {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": выполнение запроса \n";
    }
}

abstract class DecorateProcess extends ProcessRequest {
    protected $processRequest;

    public function __construct( ProcessRequest $request ) {
        $this->processRequest = $request;
    }
}

class LogRequest extends DecorateProcess {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": регистрация запроса \n";
        $this->processRequest->process( $helper );
    }
}

class AuthenticateRequest extends DecorateProcess {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": аутентификация запроса \n";
        $this->processRequest->process( $helper );
    }
}

class StructureRequest extends DecorateProcess {
    public function process( RequestHelper $helper ) {
        print __CLASS__ . ": структурирование запроса \n";
        $this->processRequest->process( $helper );
    }
}

/* Тесты
$test = new Decorator\AuthenticateRequest(
    new Decorator\StructureRequest(
        new Decorator\LogRequest(
            new Decorator\MainProcess()
        )
    ) );

$test->process( new Decorator\RequestHelper() );
*/