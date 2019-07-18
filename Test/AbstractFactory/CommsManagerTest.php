<?php

namespace Test\AbstractFactoryMethod;

use PHPUnit\Framework\TestCase;
use AbstractFactory\BloggsCommsManager;

require_once 'autoload.php';

class CommsManagerTest extends TestCase {
    public function testBloggsCommsManager() {
        // Создаём фабрику объектов типа BloggsCommsManager
        $object = new BloggsCommsManager();
        // Получаем её реализацию
        $pattern = '/Bloggs/';
        $this->assertRegExp($pattern, $object->getHeaderText());
        $this->assertRegExp($pattern, $object->getApptEncoder()->encode());
        $this->assertRegExp($pattern, $object->getTtdEncoder()->encode());
        $this->assertRegExp($pattern, $object->getContactEncoder()->encode());
        $this->assertRegExp($pattern, $object->getFooterText());
    }
}