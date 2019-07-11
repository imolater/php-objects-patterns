<?php

namespace Database\Mapper;

use Database\Domain;

class DomainObjectAssembler {
    protected static $PDO;
    protected $factory;
    protected $objectFactory;
    protected $statements = array();

    public function __construct( PersistenceFactory $factory ) {
        $this->factory = $factory;
        $this->objectFactory = $factory->getDomainObjectFactory();

        if ( ! isset( self::$PDO ) ) {
            $dsn = 'mysql:dbname=dbtokyocosmetic;host=127.0.0.1';
            $user = 'admin';
            $pwd = '123456';

            self::$PDO = new \PDO( $dsn, $user, $pwd );
            self::$PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }
    }

    private function getStatement( $stmt ): \PDOStatement {
        if ( ! isset( $this->statements[ $stmt ] ) )
            $this->statements[ $stmt ] = self::$PDO->prepare( $stmt );

        return $this->statements[ $stmt ];
    }

    public function insert( Domain\DomainObject $object ) {
        $factory = $this->factory->getUpdateFactory();
        list( $query, $values ) = $factory->newUpdate( $object );

        $stmt = $this->getStatement( $query );
        $stmt->execute( $values );
        $object->setId( self::$PDO->lastInsertId() );
        $this->objectFactory->addToMap($object);
    }

    public function select( IdentityObject $object ): Collection {
        $factory = $this->factory->getSelectFactory();
        list( $query, $values ) = $factory->newSelect( $object );

        $stmt = $this->getStatement( $query );

        return $this->factory->getCollection( $values, $stmt );
    }

    public function selectOne( IdentityObject $object ) {
        $collection = $this->select($object);
        return $collection->next();
    }
}