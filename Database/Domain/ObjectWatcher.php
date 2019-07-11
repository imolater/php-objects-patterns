<?php
/**
 * Шаблоны баз данных - Identity Map, Unity of Work
 */

namespace Database\Domain;

class ObjectWatcher {

    private $all = array();
    private $dirty = array();
    private $new = array();
    private static $instance = null;

    private function __construct() { }

    static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /* Identity Map */

    // Формируем уникальный ключ
    private function getUniqKey( DomainObject $object ) {
        $key = get_class( $object ) . "[" . $object->getId() . "]";
        return $key;
    }

    // Сохраняем в кэш
    static function add( DomainObject $object ) {
        $self = self::instance();
        $key = $self->getUniqKey( $object );
        $self->all[ $key ] = $object;
    }

    // Достаём из кэша
    static function get( $id, $class ) {
        $self = self::instance();
        $key = "{$class}[{$id}]";

        if ( isset( $self->all[ $key ] ) ) {
            return $self->all[ $key ];
        }

        return null;
    }

    /* Unit of Work */

    // Новые объекты
    static function addNew( DomainObject $object ) {
        $self = self::instance();
        // У новых объектов нет id
        $self->new[] = $object;
    }

    // Измененные объекты
    static function addDirty( DomainObject $object ) {
        $self = self::instance();

        if ( ! in_array( $object, $self->new, true ) ) {
            $key = $self->getUniqKey( $object );
            $self->dirty[ $key ] = $object;
        }
    }

    // Отмена действий для объекта
    static function cancelOperations( DomainObject $object ) {
        $self = self::instance();
        $key = $self->getUniqKey( $object );

        // Удаляем все действияы над объектом
        unset( $self->dirty[ $key ] );
        // Т.к. новые объекты добавляются без ключей, то будем
        // искать нужный нам объект функцией сравнения
        $self->new = array_filter( $self->new,
            function ( $a ) use ( $object ) {
                return ! ( $a === $object );
            } );
    }

    // Выполнение запросов к БД
    public function performOperations() {
        foreach ( $this->dirty as $key => $object ) {
            $object->mapper()->update( $object );
        }
        $this->dirty = array();

        foreach ($this->new as $key => $object) {
            $object->mapper()->insert($object);
        }
        $this->new = array();
    }
}