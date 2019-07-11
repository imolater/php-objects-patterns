<?
/**
 * Шаблон Singleton
 */
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