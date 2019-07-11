<?
/**
 * Шаблон Abstract Factory Method
 */

namespace AbstractFactoryMethod;

abstract class CommsManager {
    abstract function getHeaderText();

    abstract function getApptEncoder();

    abstract function getTtdEncoder();

    abstract function getContactEncoder();

    abstract function getFooterText();
}

class BloggsCommsManager extends CommsManager {
    public function getHeaderText() {
        return "BloggsCal верхний колонтитул\n";
    }

    public function getApptEncoder() {
        return new BloggsApptEncoder();
    }

    public function getTtdEncoder() {
        return new TtdApptEncoder();
    }

    public function getContactEncoder() {
        return new ContactApptEncoder();
    }

    public function getFooterText() {
        return "BloggsCal нижний колонтитул\n";
    }
}

abstract class ApptEncoder {

    abstract function encode();
}

class BloggsApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные зкодированы в формате BloggsCal\n";
    }
}

class TtdApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные зкодированы в формате Ttd\n";
    }
}

class ContactApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные зкодированы в формате Contact\n";
    }
}

/* Тесты
    $test = new AbstractFactoryMethod\BloggsCommsManager();
    print $test->getHeaderText();
    print $test->getApptEncoder()->encode();
    print $test->getContactEncoder()->encode();
    print $test->getTtdEncoder()->encode();
    print $test->getFooterText();
*/

