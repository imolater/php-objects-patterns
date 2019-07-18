<?php

namespace AbstractFactory\Encoder;

/**
 * Class TtdEncoder
 *
 * Интерфейс классов-продуктов типа TtdEncoder, генерируемых
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactory
 */
abstract class TtdEncoder {
    abstract function encode();
}

/**
 * Class BloggsTtdEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactory
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
 * @package AbstractFactory
 */
class MegaTtdEncoder extends TtdEncoder {
    public function encode() {
        return "Данные задач зкодированы в формате Mega\n";
    }
}
