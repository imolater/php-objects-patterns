<?php

namespace AbstractFactory\Encoder;

/**
 * Class ContactEncoder
 *
 * Интерфейс классов-продуктов типа ContactEncoder, генерируемых
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactory
 */
abstract class ContactEncoder {
    abstract function encode();
}

/**
 * Class BloggsContactEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactory
 */
class BloggsContactEncoder extends ContactEncoder {
    public function encode() {
        return "Данные контактов зкодированы в формате Bloggs\n";
    }
}

/**
 * Class MegaContactEncoder
 *
 * Конкретная реализация продукта класса-создателя MegaCommsManager.
 *
 * @package AbstractFactory
 */
class MegaContactEncoder extends ContactEncoder {
    public function encode() {
        return "Данные контактов зкодированы в формате Mega\n";
    }
}
