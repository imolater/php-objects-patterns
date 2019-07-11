<?php

namespace Database\Mapper;

use Database\Domain;

class VenueMapper extends Mapper {
    // Реализуем запросе к базе данных для работы с таблицей venue
    public function __construct() {
        parent::__construct();

        $this->selectStmt = self::$PDO->prepare(
            "SELECT * FROM venue WHERE id=?" );

        $this->updateStmt = self::$PDO->prepare(
            "UPDATE venue SET name=? WHERE id=?" );

        $this->insertStmt = self::$PDO->prepare(
            "INSERT INTO venue (name) VALUE (?)" );

        $this->selectAllStmt = self::$PDO->prepare(
            "SELECT * FROM venue"
        );
    }

    // Реализуем вставку новой записи в таблицу venue
    protected function doInsert( Domain\DomainObject $object ) {
        // Делаем дополнительную проверку типа объекта
        if ( ! ( $object instanceof Domain\Venue ) )
            throw new \Exception( 'Неверный тип объекта!' );

        // Подгатавливаем данные и выполняем запрос
        $values = [ $object->getName() ];
        $this->insertStmt->execute( $values );

        // Возвращаем полученный id
        return self::$PDO->lastInsertId();
    }

    // Реализуем обновление существующей записи в таблице venue
    protected function doUpdate( Domain\DomainObject $object ) {
        // Делаем дополнительную проверку типа объекта
        if ( ! ( $object instanceof Domain\Venue ) )
            throw new \Exception( 'Неверный тип объекта!' );

        // Подготавливаем данные и выполняем запрос
        $values = array( $object->getName(), $object->getId() );
        $this->updateStmt->execute( $values );
    }

    protected function doCreateObject( array $data ) {
        // Создаём объект
        $object = new Domain\Venue( $data[ 'name' ], $data[ 'id' ] );

        // Ищем связанные с ним объекты Space с помощью дочернего
        // класса Mapper - SpaceMapper
        $spaceMapper = new SpaceMapper();
        $spaceCollection = $spaceMapper->selectByVenue( $data[ 'id' ] );

        // Устанавливаем полученную коллекцию
        $object->setSpaces( $spaceCollection );

        return $object;
    }

    protected function getCollection( array $raw ) {
        return new VenueCollection( $raw, $this );
    }

    protected function getTargetClass() {
        return Domain\Venue::class;
    }
}
