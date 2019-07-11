<?php

namespace Database\Mapper;

use Database\Domain;

class EventMapper extends Mapper {
    private $selectBySpaceStmt;

    public function __construct() {
        parent::__construct();

        $this->selectStmt = self::$PDO->prepare(
            "SELECT * FROM event WHERE id=?"
        );

        $this->selectAllStmt = self::$PDO->prepare(
            "SELECT * FROM event"
        );

        $this->selectBySpaceStmt = self::$PDO->prepare(
            "SELECT * FROM event WHERE space=?"
        );

        $this->insertStmt = self::$PDO->prepare(
            "INSERT INTO event (name, start, duration, space) VALUE (?, ?, ?, ?)"
        );

        $this->updateStmt = self::$PDO->prepare(
            "UPDATE event SET name=?, start=?, duration=?, space=? WHERE id=?"
        );
    }

    protected function doCreateObject( array $data ) {
        $object = new Domain\Event(
            $data[ 'name' ],
            $data[ 'start' ],
            $data[ 'duration' ],
            $data[ 'space' ],
            $data[ 'id' ] );
        return $object;
    }

    protected function doInsert( Domain\DomainObject $object ) {
        if ( !( $object instanceof Domain\Event ) )
            throw new \Exception( 'Неверный тип объекта!' );

        $values = [$object->getName(), $object->getStart(), $object->getDuration(), $object->getSpaceId()];
        $this->insertStmt->execute( $values );

        return self::$PDO->lastInsertId();
    }

    protected function doUpdate( Domain\DomainObject $object ) {
        if ( !( $object instanceof Domain\Event ) )
            throw new \Exception( 'Неверный тип объекта!' );

        $values = [
            $object->getName(),
            $object->getStart(),
            $object->getDuration(),
            $object->getSpaceId(),
            $object->getId()
        ];
        $this->insertStmt->execute( $values );

        return self::$PDO->lastInsertId();
    }

    public function selectBySpace( ?int $id ) {
        return new EventDeferredCollection( array($id), $this, $this->selectBySpaceStmt );
    }

    protected function getCollection( array $raw ) {
        return new EventCollection( $raw, $this );
    }

    protected function getTargetClass() {
        return Domain\Event::class;
    }
}