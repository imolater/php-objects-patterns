<?

namespace FrontController\Command;

use FrontController\Request;

class LoginCommand extends Command {
    public function doExecute( Request $request ) {
        $request->addMessage( 'Выполнена команда ' . __CLASS__ );
        include( $this->templatesDir . "/login.php" );
    }
}