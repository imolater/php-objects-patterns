<?

namespace ApplicationController\Command;

use ApplicationController\Exception\CommandException;
use ApplicationController\Request;

abstract class Command {
    private static $statuses = array(
        'DEFAULT'        => 0,
        'OK'             => 1,
        'ERROR'          => 2,
        'INCORRECT_DATA' => 3
    );
    private $status = 0;

    final function __construct() { }

    // Выполняем команду, присваеваем ей статус выполнения
    // и сохраняем её в объекте запроса, как последнюю выполненную
    public function execute( Request $request ) {
        $this->status = $this->doExecute( $request );
        $request->setLastCommand( $this );
    }

    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     * @return mixed
     * @throws CommandException
     */
    static function getStatusCode( $status = 'DEFAULT' ) {
        if ( isset( self::$statuses[ $status ] ) ) {
            return self::$statuses[ $status ];
        }

        throw new CommandException( "Неизвестный код состояния: $status" );
    }

    abstract function doExecute( Request $request );
}