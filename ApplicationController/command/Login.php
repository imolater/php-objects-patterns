<?

namespace ApplicationController\Command;

use ApplicationController\Request;

class Login extends Command {
    public function doExecute( Request $request ) {
        $request->addFeedback( 'Выполнена команда ' . __CLASS__ );
        return self::getStatusCode('OK');
    }
}