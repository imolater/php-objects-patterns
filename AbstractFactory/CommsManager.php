<?
/**
 * Шаблон Abstract Factory
 *
 * Данный шаблон решает проблему создания экземпляров объектов,
 * используя абстрактные типы и делегируя создание экземпляров
 * объектов конкретным классам-наследникам.
 *
 * Классы создателей (CommsManager) отделены от продуктов (Encoder),
 * которые они должны генерировать.
 *
 * Создатель - это класс фабрики, в котором определен метод для
 * генерации объекта-продукта. Если стандартной реализации этого метода
 * не предусмотрено, то создание экземпляров объектов оставляют дочерним
 * классам создателя.
 *
 * Обычно в каждом подклассе создателя создается экземпляр параллельного
 * дочернего класса продукта (BloggsCommsManager -> BloggsApptEncoder)
 */

namespace AbstractFactory;


/**
 * Class CommsManager
 *
 * Интерфейс классов-создателей
 *
 * @package AbstractFactory
 */
abstract class CommsManager {
    /**
     * Метод-получатель верхнего колонтилула
     *
     * @return mixed
     */
    abstract function getHeaderText();

    /**
     * Метод-генератор класса-продукта типа ApptEncoder
     *
     * @return Encoder\ApptEncoder
     */
    abstract function getApptEncoder(): Encoder\ApptEncoder;

    /**
     * Метод-генератор класса-продукта типа TtdEncoder
     *
     * @return Encoder\TtdEncoder
     */
    abstract function getTtdEncoder(): Encoder\TtdEncoder;

    /**
     * Метод-генератор класса-продукта типа ContactEncoder
     *
     * @return Encoder\ContactEncoder
     */
    abstract function getContactEncoder(): Encoder\ContactEncoder;

    /**
     * Метод-получатель нижнего колонтилула
     *
     * @return mixed
     */
    abstract function getFooterText();
}

/**
 * Class BloggsCommsManager
 *
 * Конкретная реализация класса-создателя объектов работающих
 * с форматом Bloggs
 *
 * @package AbstractFactory
 */
class BloggsCommsManager extends CommsManager {
    public function getHeaderText() {
        return "Верхний колонтитутл в формате Bloggs\n";
    }

    public function getApptEncoder(): Encoder\ApptEncoder {
        return new Encoder\BloggsApptEncoder();
    }

    public function getTtdEncoder(): Encoder\TtdEncoder {
        return new Encoder\BloggsTtdEncoder();
    }

    public function getContactEncoder(): Encoder\ContactEncoder {
        return new Encoder\BloggsContactEncoder();
    }

    public function getFooterText() {
        return "Нижний колонтитутл в формате Bloggs\n";
    }
}

/**
 * Class MegaCommsManager
 *
 * Конкретная реализация класса-создателя объектов работающих
 * с форматом Mega
 *
 * @package AbstractFactory
 */
class MegaCommsManager extends CommsManager {
    public function getHeaderText() {
        return "Верхний колонтитутл в формате Mega\n";
    }

    public function getApptEncoder(): Encoder\ApptEncoder {
        return new Encoder\MegaApptEncoder();
    }

    public function getTtdEncoder(): Encoder\TtdEncoder {
        return new Encoder\MegaTtdEncoder();
    }

    public function getContactEncoder(): Encoder\ContactEncoder {
        return new Encoder\MegaContactEncoder();
    }

    public function getFooterText() {
        return "Нижний колонтитутл в формате Mega\n";
    }
}
