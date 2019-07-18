<?php

namespace AbstractFactory\Encoder;

/**
 * Class ApptEncoder
 *
 * Интерфейс классов-продуктов типа ApptEncoder, генерируемых
 * классами-создателями типа CommsManager
 *
 * @package AbstractFactory
 */
abstract class ApptEncoder {
    abstract function encode();
}

/**
 * Class BloggsApptEncoder
 *
 * Конкретная реализация продукта класса-создателя BloggsCommsManager.
 *
 * @package AbstractFactory
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
 * @package AbstractFactory
 */
class MegaApptEncoder extends ApptEncoder {
    public function encode() {
        return "Данные встреч зкодированы в формате Mega\n";
    }
}