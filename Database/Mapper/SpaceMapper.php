<?php

namespace Database\Mapper;

use Database\Domain;

class SpaceMapper extends Mapper {
    private $selectByVenueStmt;

    public function __construct() {
        parent::__construct();

        $this->selectStmt = self::$PDO->prepare(
            "SELECT * FROM space WHERE id=?" );

        $this->selectAllStmt = self::$PDO->prepare(
            "SELECT * FROM space"
        );

        $this->selectByVenueStmt = self::$PDO->prepare(
            "SELECT * FROM space WHERE venue=?"
        );

        $this->updateStmt = self::$PDO->prepare(
            "UPDATE space SET name=?, venue=? WHERE id=?" );

        $this->insertStmt = self::$PDO->prepare(
            "INSERT INTO space (name, venue) VALUE (?, ?)" );
    }

    protected function doCreateObject( array $data ) {
        $object = new Domain\Space( $data[ 'name' ], $data[ 'venue' ], $data[ 'id' ] );

        $eventMapper = new EventMapper();
        $eventCollection = $eventMapper->selectBySpace( $data[ 'id' ] );
        $object->setEvents( $eventCollection );

        return $object;
    }

    protected function doInsert( Domain\DomainObject $object ) {
        if ( !( $object instanceof Domain\Space ) )
            throw new \Exception( 'Неверный тип объекта!' );

        $values = array($object->getName(), $object->getVenueId());
        $this->insertStmt->execute( $values );

        return self::$PDO->lastInsertId();
    }

    protected function doUpdate( Domain\DomainObject $object ) {
        if ( !( $object instanceof Domain\Space ) )
            throw new \Exception( 'Неверный тип объекта!' );

        $values = array($object->getName(), $object->getVenueId(), $object->getId());
        $this->updateStmt->execute( $values );
    }

    public function selectByVenue( int $id ) {
        return new SpaceDeferredCollection( array($id), $this , $this->selectByVenueStmt );
    }

    public function getCollection( array $raw ) {
        return new SpaceCollection( $raw, $this );
    }

    protected function getTargetClass() {
        return Domain\Space::class;
    }
}