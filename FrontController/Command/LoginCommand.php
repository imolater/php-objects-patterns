<?

namespace FrontController\Command;

class LoginCommand extends Command {
    public function doExecute( \Registry\Request $request ) {
        $request->addMessage( 'Выполнена команда ' . __CLASS__ );
        include( $this->templatesDir . "/login.php" );
    }
}