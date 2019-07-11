<?php

namespace Database\Mapper;

use Database\Domain;

abstract class Mapper {
    protected $selectStmt;
    protected $insertStmt;
    protected $updateStmt;
    protected $selectAllStmt;
    protected static $PDO;

    public function __construct() {
        // При создании необходимо установить связь с БД
        // Откуда получать данные зависит от реализации
        if ( ! isset( self::$PDO ) ) {
            $dsn = 'mysql:dbname=dbtokyocosmetic;host=127.0.0.1';
            $user = 'admin';
            $pwd = '123456';

            self::$PDO = new \PDO( $dsn, $user, $pwd );
            self::$PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }
    }

    // Выборка по id из БД
    public function select( $id ) {
        // Смотрим, есть ли объект в кэше
        $cacheObj = $this->getFromMap( $id );
        if ( ! is_null( $cacheObj ) )
            return $cacheObj;

        // Если нет, то выполняем запрос к БД
        // Получаем конкретный запрос
        $stmt = $this->selectStmt();
        // Выполняем его
        $stmt->execute( array( $id ) );
        $result = $stmt->fetch( \PDO::FETCH_ASSOC );
        $stmt->closeCursor();

        // Если данные получены, создаём объект
        if ( ! is_array( $result ) || ! isset( $result[ 'id' ] ) )
            return null;

        $object = $this->createObject( $result );
        return $object;
    }

    public function selectAll(): Collection {
        $stmt = $this->selectAllStmt();
        $stmt->execute();
        $data = $stmt->fetchAll( \PDO::FETCH_ASSOC );

        return $this->getCollection( $data );
    }

    // Новая запись в БД
    public function insert( Domain\DomainObject $object ) {
        // Выполняем конкретный метод
        $id = $this->doInsert( $object );
        // Ставим объекту id из БД
        $object->setId( $id );

        // После вставки объекта, он становится "доведенным до ума",
        // поэтому, его также нужно поместить в кэш
        $this->addToMap( $object );
    }

    // Обновление существующей записи в БД
    public function update( Domain\DomainObject $object ) {
        // Объект есть в БД, только если у него есть id
        if ( is_null( $object->getId() ) )
            throw new \Exception( 'Не указан id!' );

        // Выполняем конкретный метод
        $this->doUpdate( $object );
    }

    // Создание объекта
    public function createObject( array $data ) {
        // Смотрим, есть ли объект в кэше
        $cacheObj = $this->getFromMap( $data[ 'id' ] );
        if ( ! is_null( $cacheObj ) )
            return $cacheObj;

        // Если нет, то создаём его и помещаем в кэш
        $object = $this->doCreateObject( $data );
        $this->addToMap( $object );

        return $object;
    }

    // Получение конкретной реализации запроса SELECT
    private function selectStmt(): \PDOStatement {
        return $this->selectStmt;
    }

    private function selectAllStmt(): \PDOStatement {
        return $this->selectAllStmt;
    }

    /* Методы для работы с объектом кэширования - ObjectWatcher */

    // Достаём из кэша
    private function getFromMap( $id ) {
        $class = $this->getTargetClass();
        return Domain\ObjectWatcher::get( $id, $class );
    }

    // Добавляем в кэш
    private function addToMap( Domain\DomainObject $object ) {
        Domain\ObjectWatcher::add( $object );
    }

    /* Конец */

    protected abstract function getCollection( array $raw );

    protected abstract function getTargetClass();

    protected abstract function doCreateObject( array $data );

    protected abstract function doInsert( Domain\DomainObject $object );

    protected abstract function doUpdate( Domain\DomainObject $object );
}