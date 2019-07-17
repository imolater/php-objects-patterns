<?php

namespace Test\FrontController;

use Facebook\WebDriver;
use Facebook\WebDriver\Remote;
use PHPUnit\Framework\TestCase;

/**
 * Class ControllerTest
 *
 * Для запуска требуется:
 * Локальный интерепретатор PHP
 * Chrome v74
 * Страница с запуском скрипта FrontController\Controller::run();
 *
 * @package Test\FrontController;
 */
class ControllerTest extends TestCase {
    /**
     * @var Remote\RemoteWebDriver Драйвер управления браузером
     */
    private $driver;
    private $rooDir = "http://tokyocosmetic.dev.ram/PHPObjectPatterns";

    protected function setUp() {
        $host = 'http://localhost:4444/wd/hub';
        $this->driver = Remote\RemoteWebDriver::create($host, Remote\DesiredCapabilities::chrome());
    }

    public function testLoginCommand() {
        $this->driver->get( $this->rooDir . '/index.php?action=login');

        $loginInput = $this->driver->findElement(WebDriver\WebDriverBy::name('login'));
        $loginInput->sendKeys('admin');

        $this->assertEquals('admin', $loginInput->getAttribute('value'));
    }
}