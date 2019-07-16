<?

namespace ApplicationController;

use ApplicationController\Command\Command;

class Request {
    private $storage = array();
    private $templateData = array();

    public function __construct() {
        $this->init();
    }

    public function init() {
        if ( isset( $_SESSION[ 'REQUEST_METHOD' ] ) ) {
            $this->storage = $_REQUEST;
            return;
        }

        foreach ( $_SERVER[ 'argv' ] as $arg ) {
            if ( strpos( $arg, '=' ) ) {
                list( $key, $value ) = explode( '=', $arg );
                $this->setProperty( $key, $value );
            }
        }

        $this->setProperty( 'action', 'AddVenue' );
        $this->setProperty('venueName', 'Рыба-Меч');
    }

    public function getProperty( $key ) {
        if ( isset( $this->storage[ $key ] ) ) {
            return $this->storage[ $key ];
        }

        return null;
    }

    public function setProperty( $key, $value ) {
        $this->storage[ $key ] = $value;
    }

    // Конкретный геттер для получения последней команды
    // из хранилища настроек запроса
    public function getLastCommand() {
        if ( isset( $this->storage[ 'lastCmd' ]) ) {
            return $this->storage[ 'lastCmd' ];
        }

        return null;
    }

    // Конкретный сеттер для последней команды
    public function setLastCommand( Command $cmd ) {
        $this->storage[ 'lastCmd' ] = $cmd;
    }

    // Добавление данных в итоговый массив, который
    // попадет в шаблон
    public function addTemplateData( $key, $value ) {
        $this->templateData[$key] = $value;
    }

    /**
     * @return array
     */
    // Извлечение итоговых данных
    public function getTemplateData(): array {
        return $this->templateData;
    }
}