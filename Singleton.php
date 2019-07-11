<?
/**
 * Шаблон Singleton
 */

namespace Singleton;

class Preferences {
    private $props = array();
    private static $instance;

    // Блокируем конструктор
    private function __construct() { }

    // При первом обращение создаём объект, а далее
    // всегда возвращаем его же
    public static function getInstance(): Preferences {
        if ( empty( self::$instance ) ) {
            self::$instance = new Preferences();
        }
        return self::$instance;
    }

    // Устанавливаем любые свойства
    public function setProperty( $key, $value ) {
        $this->props[ $key ] = $value;
    }

    // Получаем эти свойства
    public function getProperty( $key ) {
        return $this->props[ $key ];
    }
}

/* Тесты
// Создаём объект
$test = Singleton\Preferences::getInstance();
// Устанавливаем свойство
$test->setProperty( 'name', 'Иван' );
// Записываем объект в новую переменную
$test2 = Singleton\Preferences::getInstance();
// Меняем свойство
$test2->setProperty( 'name', 'Алёша' );
// Получаем один и тот же объект в обоих переменных
print $test->getProperty( 'name' );
print $test2->getProperty( 'name' );
*/