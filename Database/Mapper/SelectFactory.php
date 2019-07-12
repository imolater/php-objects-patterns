<?php

namespace Database\Mapper;

abstract class SelectFactory {
    public function newSelect( IdentityObject $object ) {
        $fields = implode(', ', $object->getAllowedFields());
        $conditions = null;

        if (! $object->isVoid())
            $conditions = $object->getComparisons();

        return $this->buildStatement($this->getTableName(), $fields, $conditions);
    }

    protected function buildStatement( $table, $fields, $conditions = null ) {
        $values = array();
        $query = "SELECT {$fields} FROM {$table}";

        if (!is_null($conditions)) {
            $queryCond = array();

            foreach ( $conditions as $condition ) {
                $queryCond[] = "{$condition['name']} {$condition['operator']} ?";
                $values[] = $condition[ 'value' ];
            }

            $query .= " WHERE " . implode( " AND ", $queryCond );
        }

        return array($query, $values);
    }

    abstract function getTableName();
}

class VenueSelectFactory extends SelectFactory {
    public function getTableName() {
        return 'venue';
    }

}

class SpaceSelectFactory extends SelectFactory {
    public function getTableName() {
        return 'space';
    }
}

class EventSelectFactory extends SelectFactory {
    public function getTableName() {
        return 'event';
    }
}

/* Тесты
    // Создаём фабрику персистентности
    $persistence = new \Database\Mapper\VenuePersistenceFactory();
    // Получаем фабрику запросов select
    $select = $persistence->getSelectFactory();
    // Создаём подготовитель данных запросов
    $idObj = new \Database\Mapper\VenueIdentityObject();
    $idObj->field('name')->eq('happy');
    // Строим запрос
    $query = $select->newSelect($idObj);
    print_r($query);
*/