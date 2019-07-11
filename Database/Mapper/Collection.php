<?php
/**
 * Шаблоны баз данных
 */

namespace Database\Mapper;

use Database\Domain;

abstract class Collection implements \Iterator {
    protected $mapper;
    protected $count = 0;
    protected $raw = [];

    private $pointer = 0;
    private $objects = [];

    function __construct( array $raw = null, Mapper $mapper = null ) {
        // При создании можно передать массив данных, из которых
        // будут создаваться объекты.
        // В таком случае, также нужно передать Mapper, с помощью которого
        // эти объекты и будут создаваться
        if ( !is_null( $raw ) && !is_null( $mapper ) ) {
            $this->raw = $raw;
            $this->count = count( $raw );
        }

        $this->mapper = $mapper;
    }

    protected function notifyAccess() {
        // Метод будет определен в дочерних классах конкретных коллекций
        // для для того, чтобы отложить преобразование данных в объекты
        // до момента реального обращения клиентского кода
    }

    // Добавление объекта в коллекцию
    public function add( Domain\DomainObject $object ) {
        // Проверяем тип
        $class = $this->getTargetClass();

        if ( !( $object instanceof $class ) )
            throw new \Exception( "Это коллекция класса - $class" );

        // Активируем отложенную загрузку данных
        $this->notifyAccess();
        $this->objects[ $this->count ] = $object;
        $this->count++;
    }

    // Получением объекта из коллекции
    private function getRow( $number ) {
        // Активируем отложенную загрузку данных
        $this->notifyAccess();

        if ( isset( $this->objects[ $number ] ) ) {
            return $this->objects[ $number ];
        }

        // Если нет, смотрим, есть ли данные,
        // из которых мы можем создать объект
        if ( isset( $this->raw[ $number ] ) ) {
            $object = $this->mapper->createObject( $this->raw[ $number ] );
            $this->objects[ $number ] = $object;
            return $object;
        }

        // В противном случае null
        return null;
    }

    abstract function getTargetClass();

    // Реализация интерфейса Iterator
    public function rewind() {
        $this->pointer = 0;
    }

    public function current() {
        return $this->getRow( $this->pointer );
    }

    public function key() {
        return $this->pointer;
    }

    public function next() {
        $row = $this->getRow( $this->pointer );

        if ( $row )
            $this->pointer++;

        return $row;
    }

    public function valid() {
        return ( !is_null( $this->current() ) );
    }

    // Реализация Generator
    public function getGenerator() {
        for ( $i = 0; $i < $this->count; $i++ ) {
            yield( $this->getRow( $i ) );
        }
    }
}

class VenueCollection extends Collection {
    public function getTargetClass() {
        return Domain\Venue::class;
    }
}

class SpaceCollection extends Collection {
    public function getTargetClass() {
        return Domain\Space::class;
    }
}

class EventCollection extends Collection {
    function getTargetClass() {
        return Domain\Event::class;
    }
}