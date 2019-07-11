<?php
/**
 * Шаблоны для работы с базой данных
 */

namespace Database\Mapper;

abstract class PersistenceFactory {
    protected $domainObjectFactory;
    protected $selectFactory;
    protected $updateFactory;

    public static function getFactory( string $type ): PersistenceFactory {
        $class = str_replace( 'Domain', 'Mapper', $type );
        $factory = $class . "PersistenceFactory";
        return new $factory();
    }

    public static function getAssembler( string $type ): DomainObjectAssembler {
        $factory = self::getFactory( $type );
        return new DomainObjectAssembler( $factory );
    }

    public function getDomainObjectFactory(): DomainObjectFactory {
        if ( is_null( $this->domainObjectFactory ) )
            $this->domainObjectFactory = $this->createDomainObjectFactory();

        return $this->domainObjectFactory;
    }

    public function getSelectFactory(): SelectFactory {
        if ( is_null( $this->selectFactory ) )
            $this->selectFactory = $this->createSelectFactory();

        return $this->selectFactory;
    }

    public function getUpdateFactory(): UpdateFactory {
        if ( is_null( $this->updateFactory ) )
            $this->updateFactory = $this->createUpdateFactory();

        return $this->updateFactory;
    }

    abstract function createDomainObjectFactory(): DomainObjectFactory;

    abstract function createSelectFactory(): SelectFactory;

    abstract function createUpdateFactory(): UpdateFactory;

    abstract function getCollection( array $raw, \PDOStatement $stmt );
}

class VenuePersistenceFactory extends PersistenceFactory {
    public function createDomainObjectFactory(): DomainObjectFactory {
        return new VenueDomainObjectFactory();
    }

    public function createSelectFactory(): SelectFactory {
        return new VenueSelectFactory();
    }

    public function createUpdateFactory(): UpdateFactory {
        return new VenueUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): VenueCollection {
        return new VenueDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}

class SpacePersistenceFactory extends PersistenceFactory {
    public function createDomainObjectFactory(): DomainObjectFactory {
        return new SpaceDomainObjectFactory();
    }

    public function createSelectFactory(): SelectFactory {
        return new SpaceSelectFactory();
    }

    public function createUpdateFactory(): UpdateFactory {
        return new SpaceUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): SpaceCollection {
        return new SpaceDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}

class EventPersistenceFactory extends PersistenceFactory {
    public function createDomainObjectFactory(): DomainObjectFactory {
        return new EventDomainObjectFactory();
    }

    public function createSelectFactory(): SelectFactory {
        return new EventSelectFactory();
    }

    public function createUpdateFactory(): UpdateFactory {
        return new EventUpdateFactory();
    }

    public function getCollection( array $raw, \PDOStatement $stmt ): EventCollection {
        return new EventDeferredCollection($raw, $this->getDomainObjectFactory(), $stmt);
    }
}