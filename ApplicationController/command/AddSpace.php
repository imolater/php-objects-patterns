<?

namespace ApplicationController\Command;

use ApplicationController\Request;

class AddSpace extends Command {
    public function doExecute( Request $request ) {
        $request->addTemplateData('sucMsg', __CLASS__ . "Имитация успешного добавления места к заведению!");
        return self::getStatusCode('OK');
    }
}