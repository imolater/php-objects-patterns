<?

namespace FrontController\Command;

use FrontController\Request;

abstract class Command {
    protected $templatesDir;

    final function __construct() {
        $this->templatesDir = dirname(__FILE__, 2) . "/view";
    }

    public function execute( Request $request ) {
        $this->doExecute( $request );
    }

    abstract function doExecute( Request $request );
}

class DefaultCommand extends Command {
    public function doExecute( Request $request ) {
        $request->addMessage( "Добро пожаловать в Woo!" );
        include( $this->templatesDir . '/main.php');
    }
}