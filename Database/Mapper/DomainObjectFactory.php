<?php

namespace Database\Mapper;

use Database\Domain;

abstract class DomainObjectFactory {
    // Создать объект
    public function createObject(array $data) {
        // Смотрим есть ли в кэше
        $cacheObj = $this->getFromMap($data['id']);

        if (! is_null($cacheObj))
            return $cacheObj;

        // Если нет, то используем конкретную реализацию для
        // создания и помещаем в кэш
        $object = $this->doCreateObject($data);
        $this->addToMap($object);

        return $object;
    }

    // Взять из кэша
    public function getFromMap( $id ) {
        $class = $this->getTargetClass();
        return Domain\ObjectWatcher::get( $id, $class );
    }

    // Добавить в кэш
    public function addToMap( Domain\DomainObject $object ) {
        Domain\ObjectWatcher::add( $object );
    }

    abstract function doCreateObject(array $data);

    abstract function getTargetClass();
}

class VenueDomainObjectFactory extends DomainObjectFactory {
    public function doCreateObject( array $data ): Domain\Venue {
        $object = new Domain\Venue( $data[ 'name'], $data[ 'id']);

        $mapper = PersistenceFactory::getAssembler(Domain\Space::class);
        $query = new SpaceIdentityObject();
        $query->field('venue')->eq($data['id']);

        $collection = $mapper->select($query);
        $object->setSpaces($collection);

        return $object;
    }

    public function getTargetClass() {
        return Domain\Venue::class;
    }
}

class SpaceDomainObjectFactory extends DomainObjectFactory {
    public function doCreateObject( array $data ): Domain\Space {
        $object = new Domain\Space( $data[ 'name'], $data[ 'venue'], $data[ 'id']);
        return $object;
    }

    public function getTargetClass() {
        return Domain\Space::class;
    }
}

class EventDomainObjectFactory extends DomainObjectFactory {
    public function doCreateObject( array $data ): Domain\Event {
        $object = new Domain\Event( $data[ 'name'], $data[ 'start'], $data[ 'duration'], $data[ 'space'], $data[ 'id']);
        return $object;
    }

    public function getTargetClass() {
        return Domain\Event::class;
    }
}