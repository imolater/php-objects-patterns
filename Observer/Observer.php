<?
/**
 * Шаблон Observer
 */

namespace Observer;

abstract class LoginObserver implements \SplObserver {
    private $login;

    public function __construct( Login $login ) {
        $this->login = $login;
        $login->attach( $this );
    }

    public function update( \SplSubject $subject ) {
        /** @var Login $subject */
        $this->doUpdate( $subject );
    }

    abstract protected function doUpdate( Login $login );
}

class SecurityMonitorObserver extends LoginObserver {
    protected function doUpdate( Login $login ) {
        print __CLASS__ . " : Отправка почты системному администратору\n";
    }
}

class GeneralLoggerObserver extends LoginObserver {
    protected function doUpdate( Login $login ) {
        print __CLASS__ . " : Регистрация в системном журнале\n";
    }
}

class PartnershipToolObserver extends LoginObserver {
    protected function doUpdate( Login $login ) {
        print __CLASS__ . " : Отправка сооkiе-файла, если адрес соответствует списку\n";
    }
}