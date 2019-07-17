<?php

namespace AbstractFactoryMethod\Encoder;

/**
 * Class ApptEncoder
 *
 * Интерфейс классов-продуктов типа ApptEncoder, генерируемые
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactoryMethod
 */
abstract class ApptEncoder {
    abstract function encode();
}

/**
 * Class BloggsApptEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactoryMethod
 */
class BloggsApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные встреч зкодированы в формате Bloggs\n";
    }
}

/**
 * Class MegaApptEncoder
 *
 * Конкретная реализация продукта класса-создателя MegaCommsManager.
 *
 * @package AbstractFactoryMethod
 */
class MegaApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные встреч зкодированы в формате Mega\n";
    }
}