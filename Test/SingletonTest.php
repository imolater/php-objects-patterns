<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Singleton\Preferences;

require_once 'autoload.php';

class SingletonTest extends TestCase {
    public function testGetInstance() {
        // Получаем объект и устанавливаем свойство
        $object = Preferences::getInstance();
        $object->setProperty( 'name', 'Иван' );

        // Пробуем получить другой объект и работать уже с ним
        $anotherObject = Preferences::getInstance();
        $anotherObject->setProperty( 'name', 'Алёша' );

        // Но обе переменные хранят ссылку на один и тот же объект
        $this->assertEquals($object->getProperty('name'), $anotherObject->getProperty('name'));
    }
}