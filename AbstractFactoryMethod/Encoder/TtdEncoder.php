<?php

namespace AbstractFactoryMethod\Encoder;

/**
 * Class TtdEncoder
 *
 * Интерфейс классов-продуктов типа TtdEncoder, генерируемые
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactoryMethod
 */
abstract class TtdEncoder {
    abstract function encode();
}

/**
 * Class BloggsTtdEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactoryMethod
 */
class BloggsTtdEncoder extends TtdEncoder {
    public function encode() {
        return "Данные задач зкодированы в формате Bloggs\n";
    }
}

/**
 * Class MegaTtdEncoder
 *
 * Конкретная реализация продукта класса-создателя MegaCommsManager.
 *
 * @package AbstractFactoryMethod
 */
class MegaTtdEncoder extends TtdEncoder {
    public function encode() {
        return "Данные задач зкодированы в формате Mega\n";
    }
}
