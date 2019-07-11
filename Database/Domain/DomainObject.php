<?php
/**
 * Шаблоны баз данных - Domain Model
 */

namespace Database\Domain;

abstract class DomainObject {
    private $id;
    private $name;

    public function __construct( $name, $id = null, $isNew = false ) {
        // Требуем у клиентского кода помечать объект как новый
        if ($isNew) $this->markNew();

        $this->id = $id;
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId( int $id ): void {
        $this->id = $id;
    }

    // Клиентский метод изменения имени
    public function setName( string $name ): void {
        $this->name = $name;
        // Помечаем объект как измененный
        $this->markChanged();
    }

    /* Unity of Work */

    // Помечаем объект как новый
    public function markNew() {
        ObjectWatcher::addNew( $this );
    }

    // Помечаем объект как измененный
    public function markChanged() {
        ObjectWatcher::addDirty( $this );
    }

    // Отменяем все действия над объектом
    public function discardChanges() {
        ObjectWatcher::cancelOperations( $this );
    }

    static function getCollection( $type = null ) {
        if ( is_null( $type ) )
            $type = get_called_class();

        return HelperFactory::getCollection( $type );
    }

    public function collection() {
        return HelperFactory::getCollection( get_called_class() );
    }

    static function getMapper( $type = null ) {
        if ( is_null( $type ) )
            $type = get_called_class();

        return HelperFactory::getMapper( $type );
    }

    public function mapper() {
        return HelperFactory::getMapper( get_called_class() );
    }
}