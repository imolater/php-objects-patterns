<?php
/**
 * Шаблоны для работы с базой данных
 */

namespace Database\Mapper;

abstract class PersistenceFactory {
    protected $domainObjectFactory;
    protected $identityObject;
    protected $selectFactory;
    protected $updateFactory;

    // Статичный метод получения нужной нам фабрики персистентности
    public static function getFactory( string $type ): PersistenceFactory {
        $class = str_replace( 'Domain', 'Mapper', $type );
        $factory = $class . "PersistenceFactory";
        return new $factory();
    }

    // Статичный метод получения сборщика доменных объектов
    public static function getAssembler( string $type ): DomainObjectAssembler {
        $factory = self::getFactory( $type );
        return new DomainObjectAssembler( $factory );
    }


    // Получение фабрики объектов
    public function getDomainObjectFactory(): DomainObjectFactory {
        if ( is_null( $this->domainObjectFactory ) )
            $this->domainObjectFactory = $this->createDomainObjectFactory();

        return $this->domainObjectFactory;
    }

    // Получение фабрики данных запросов
    public function getIdentityObject(): IdentityObject {
        if ( is_null( $this->identityObject ) )
            $this->identityObject = $this->createIdentityObject();

        return $this->identityObject;
    }

    // Получение фабрики запросов select
    public function getSelectFactory(): SelectFactory {
        if ( is_null( $this->selectFactory ) )
            $this->selectFactory = $this->createSelectFactory();

        return $this->selectFactory;
    }

    // Получение фабрики запросов update
    public function getUpdateFactory(): UpdateFactory {
        if ( is_null( $this->updateFactory ) )
            $this->updateFactory = $this->createUpdateFactory();

        return $this->updateFactory;
    }

    abstract protected function createDomainObjectFactory(): DomainObjectFactory;

    abstract protected function createIdentityObject(): IdentityObject;

    abstract protected function createSelectFactory(): SelectFactory;

    abstract protected function createUpdateFactory(): UpdateFactory;

    abstract function getCollection( array $raw, \PDOStatement $stmt );
}

class VenuePersistenceFactory extends PersistenceFactory {
    protected function createDomainObjectFactory(): DomainObjectFactory {
        return new VenueDomainObjectFactory();
    }

    protected function createIdentityObject(): IdentityObject {
        return new VenueIdentityObject();
    }

    protected function createSelectFactory(): SelectFactory {
        return new VenueSelectFactory();
    }

    protected function createUpdateFactory(): UpdateFactory {
        return new VenueUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): VenueCollection {
        return new VenueDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}

class SpacePersistenceFactory extends PersistenceFactory {
    protected function createDomainObjectFactory(): DomainObjectFactory {
        return new SpaceDomainObjectFactory();
    }

    protected function createIdentityObject(): IdentityObject {
        return new SpaceIdentityObject();
    }

    protected function createSelectFactory(): SelectFactory {
        return new SpaceSelectFactory();
    }

    protected function createUpdateFactory(): UpdateFactory {
        return new SpaceUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): SpaceCollection {
        return new SpaceDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}

class EventPersistenceFactory extends PersistenceFactory {
    protected function createDomainObjectFactory(): DomainObjectFactory {
        return new EventDomainObjectFactory();
    }

    protected function createIdentityObject(): IdentityObject {
        return new EventIdentityObject();
    }

    protected function createSelectFactory(): SelectFactory {
        return new EventSelectFactory();
    }

    protected function createUpdateFactory(): UpdateFactory {
        return new EventUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): EventCollection {
        return new EventDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}

/* Тесты
    // Получаем сборщик доменных объектов
    $mapper = \Database\Mapper\PersistenceFactory::getAssembler(\Database\Domain\Venue::class);
    // Достаём фабрику данных запросов
    $idObj = $mapper->factory->getIdentityObject();
    // Формируем данные для запроса
    $query = $idObj->field('id')->eq('1');
    // Выполняем запрос
    $venue = $mapper->selectOne($query);

    // Пробегаем коллекцию в цикле
    foreach ($venue->getSpaces() as $space) {
        print $space->getName() . "\n";
    }
*/