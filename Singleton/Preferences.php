<?
namespace Singleton;

/**
 * Class Preferences - реализация шаблона Singleton
 *
 * Шаблон Singleton это объектно-ориентированная реализация
 * глобальных переменных. Такие объекты предназначены для сохранения
 * данных, которые должны быть легкодоступны и надежно сохранены,
 * на протяжение всего процесса выполнения сценария. Например, параметры
 * подключения к БД, пути к файлам, URL сайта, данные запроса и т.д.
 *
 * Данный шаблон предполагает запрет на создание экземпляров класса
 * за его пределами, путем ограничения области видимости конструктора.
 * Для получения объекта используется статический метод getInstance(),
 * а ссылка на него хранится в закрытом свойстве instance.
 *
 * @package Singleton
 * @see Preferences::getInstance()
 */
class Preferences {
    /**
     * Массив хранимых свойств приложения
     *
     * @var array
     */
    private $props = array();
    /**
     * Ссылка на единственный экземпляр класса
     *
     * @var Preferences
     */
    private static $instance;

    /**
     * Конструктор класса
     */
    private function __construct() { }

    /**
     * Метод получения единственного экземпляра класса
     *
     * Свойство instance - закрытое и статическое, поэтому к нему нельзя
     * получить доступ из-за пределов класса. Но у метода getInstance()
     * есть доступ к нему. Поскольку метод getInstance() - общедоступный
     * и статический, его можно вызвать через класс из любого места сценария.
     * Тем самым мы создаём экземпляр класса только один раз - при первом
     * обращение к методу getInstance(), а при последующих обращениях
     * возвращаем сохраенную в кэше ссылку.
     *
     * @return Preferences
     */
    public static function getInstance(): Preferences {
        if ( empty( self::$instance ) ) {
            self::$instance = new Preferences();
        }
        return self::$instance;
    }

    /**
     * Метод-установщик
     *
     * @param $key
     * @param $value
     */
    public function setProperty( $key, $value ) {
        $this->props[$key] = $value;
    }

    /**
     * Метод-получатель
     *
     * @param $key
     *
     * @return mixed
     */
    public function getProperty( $key ) {
        return $this->props[$key];
    }
}