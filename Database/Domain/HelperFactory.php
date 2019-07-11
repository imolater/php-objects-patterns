<?php
/**
 * Шаблоны баз данных
 */

namespace Database\Domain;

use Database\Mapper;

class HelperFactory {
    public static function getCollection( string $type ): Mapper\Collection {
        $class = str_replace( 'Domain', 'Mapper', $type );
        $collection = $class . "Collection";
        return new $collection();
    }

    public static function getMapper( string $type ): Mapper\Mapper {
        $class = str_replace( 'Domain', 'Mapper', $type );
        $mapper = $class . "Mapper";
        return new $mapper();
    }
}