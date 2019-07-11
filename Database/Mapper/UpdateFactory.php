<?php

namespace Database\Mapper;

use Database\Domain;

abstract class UpdateFactory {
    abstract function newUpdate( Domain\DomainObject $object );

    protected function buildStatement( $table, array $fields, array $conditions = null ) {
        $values = array();
        $queryValues = array();

        // Случай UPDATE
        if ( ! is_null( $conditions ) ) {
            $values = array_values($fields);

            $query = "UPDATE {$table} SET ";
            $query .= implode( " = ?, ", array_keys( $fields ) ) . " = ?";
            $query .= " WHERE ";

            foreach ($conditions as $key => $value) {
                $queryValues[] = "{$key} = ?";
                $values[] = $value;
            }

            $query .= implode(" AND ", $queryValues);
        } else {
            // Случай INSERT
            $query = "INSERT INTO {$table} (";
            $query .= implode(", ", array_keys($fields));
            $query .= " ) VALUES (";

            foreach ($fields as $name => $value) {
                $queryValues[] = "?";
                $values[] = $value;
            }

            $query .= implode(",", $queryValues);
            $query .= ")";
        }

        return array($query, $values);
    }
}

class VenueUpdateFactory extends UpdateFactory {
    public function newUpdate( Domain\DomainObject $object ) {
        if ( ! $object instanceof Domain\Venue)
            throw new \Exception('Неверный тип объекта');

        $id = $object->getId();
        $conditions = null;

        if (! is_null($id))
            $conditions['id'] = $id;

        $fields['name'] = $object->getName();

        return $this->buildStatement("venue", $fields, $conditions);
    }
}

class SpaceUpdateFactory extends UpdateFactory {
    public function newUpdate( Domain\DomainObject $object ) {
        if ( ! $object instanceof Domain\Space)
            throw new \Exception('Неверный тип объекта');

        $id = $object->getId();
        $conditions = null;

        // Если есть id, то это существующий в БД объект, значит
        // добавляем условия для UPDATE
        if (! is_null($id))
            $conditions['id'] = $id;

        $fields = array(
            'name' => $object->getName(),
            'venue' => $object->getVenue()
        );

        return $this->buildStatement("space", $fields, $conditions);
    }
}

class EventUpdateFactory extends UpdateFactory {
    public function newUpdate( Domain\DomainObject $object ) {
        if ( ! $object instanceof Domain\Event)
            throw new \Exception('Неверный тип объекта');

        $id = $object->getId();
        $conditions = null;

        // Если есть id, то это существующий в БД объект, значит
        // добавляем условия для UPDATE
        if (! is_null($id))
            $conditions['id'] = $id;

        $fields = array(
            'name' => $object->getName(),
            'start' => $object->getStart(),
            'duration' => $object->getDuration(),
            'space' => $object->getSpace()
        );

        return $this->buildStatement("event", $fields, $conditions);
    }
}