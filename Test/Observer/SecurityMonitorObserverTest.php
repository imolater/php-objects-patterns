<?php

namespace Test\Observer;

require_once 'autoload.php';

use Observer;
use PHPUnit\Framework\TestCase;

class SecurityMonitorObserverTest extends TestCase {
    private $login;

    protected function setUp() {
        $this->login = new Observer\Login();
    }

    public function testUpdate() {
        ob_start();

        new Observer\SecurityMonitorObserver( $this->login );
        $this->login->notify();

        $content = ob_get_contents();
        ob_end_clean();

        $this->assertRegExp('/Отправка почты системному администратору/', $content);
    }
}
