<?php

namespace AbstractFactoryMethod\Encoder;

/**
 * Class ContactEncoder
 *
 * Интерфейс классов-продуктов типа ContactEncoder, генерируемые
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactoryMethod
 */
abstract class ContactEncoder {
    abstract function encode();
}

/**
 * Class BloggsContactEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactoryMethod
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
 * @package AbstractFactoryMethod
 */
class MegaContactEncoder extends ContactEncoder {
    public function encode() {
        return "Данные контактов зкодированы в формате Mega\n";
    }
}
