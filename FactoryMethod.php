<?
/**
 * Шаблон Factory Method
 */

namespace FactoryMethod;

abstract class CommsManager {

    abstract function getHeaderText();

    abstract function getApptEncoder();

    abstract function getFooterText();
}

class BloggsCommsManager extends CommsManager {
    public function getHeaderText() {
        return "Верхний колонтитутл\n";
    }

    public function getApptEncoder() {
        return new BloggsApptEncoder();
    }

    public function getFooterText() {
        return "Нижний колонтитул\n";
    }
}

abstract class ApptEncoder {
    abstract function encode();
}

class BloggsApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные закодированы в формате BloggsCall\n";
    }
}

/* Тесты
$test = new FactoryMethod\BloggsCommsManager();
print $test->getHeaderText();
print $test->getApptEncoder()->encode();
print $test->getFooterText();
*/