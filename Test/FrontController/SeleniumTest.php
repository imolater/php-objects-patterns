<?php

use Facebook\WebDriver;
use Facebook\WebDriver\Remote;

class SeleniumTest extends \PHPUnit\Framework\TestCase {
    /**
     * @var Remote\RemoteWebDriver Драйвер управления браузером
     */
    private $driver;

    protected function setUp() {
        $host = 'http://localhost:4444/wd/hub';
        $this->driver = Remote\RemoteWebDriver::create($host, Remote\DesiredCapabilities::chrome());
    }

    public function testLoginCommand() {
        $root = "http://tokyocosmetic.dev.ram/test/PHP-Object-Patterns";
        $this->driver->get( $root . '/index.php?action=login');

        $loginInput = $this->driver->findElement(WebDriver\WebDriverBy::name('login'));
        $loginInput->sendKeys('admin');

        $this->assertEquals('admin', $loginInput->getAttribute('value'));
    }
}