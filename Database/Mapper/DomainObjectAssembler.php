<?php

namespace Database\Mapper;

use Database\Domain;
use Registry\ApplicationRegistry;

class DomainObjectAssembler {
    public $factory;
    protected static $PDO;
    protected $objectFactory;
    protected $statements = array();

    public function __construct( PersistenceFactory $factory ) {
        // Сохраняем фабрику персистентности
        $this->factory = $factory;
        // Получаем конструктор объектов
        $this->objectFactory = $factory->getDomainObjectFactory();

        // При создании необходимо установить связь с БД
        // Откуда получать данные зависит от реализации
        $db = ApplicationRegistry::getDSN();
        if ( is_null( $db ) ) {
            throw new \Exception( 'DSN не определён!' );
        }

        self::$PDO = new \PDO( $db['dsn'], $db['login'], $db['password'] );
        self::$PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }

    // Получаем объект запроса для нашего движка связи с БД
    private function getStatement( $stmt ): \PDOStatement {
        if ( !isset( $this->statements[$stmt] ) )
            $this->statements[$stmt] = self::$PDO->prepare( $stmt );

        return $this->statements[$stmt];
    }

    // Вставка
    public function insert( Domain\DomainObject $object ) {
        // Достаем фабрику запросов update
        $factory = $this->factory->getUpdateFactory();
        // Формируем запрос и данные для него
        list( $query, $values ) = $factory->newUpdate( $object );

        // Выполняем запрос
        $stmt = $this->getStatement( $query );
        $stmt->execute( $values );
        // Присваем объекту id из базы
        $object->setId( self::$PDO->lastInsertId() );
        // Кэшируем объект
        $this->objectFactory->addToMap( $object );
    }

    // Множественная выборка
    public function select( IdentityObject $object ): Collection {
        // Достаем фабрику запросов select
        $factory = $this->factory->getSelectFactory();
        // Формируем запрос и данные для него
        list( $query, $values ) = $factory->newSelect( $object );

        // Оборачиваем запрос в объект, понятный движку
        $stmt = $this->getStatement( $query );

        // Возвращаем коллекцию с отложенной загрузкой
        return $this->factory->getCollection( $values, $stmt );
    }

    // Выборка одной записи
    public function selectOne( IdentityObject $object ) {
        // Даже один объект получим в через функцию множественной выборки
        $collection = $this->select( $object );
        // Сразу активируем загрузку
        return $collection->next();
    }
}

/* Тесты
    // Получаем сборщик доменных объектов
    $mapper = \Database\Mapper\PersistenceFactory::getAssembler(\Database\Domain\Venue::class);
    // Достаём фабрику данных запросов
    $idObj = $mapper->factory->getIdentityObject();
    // Формируем данные для запроса
    $query = $idObj->field('id')->eq('1');
    // Выполняем запрос
    $venue = $mapper->selectOne($query);

    // Пробегаем коллекцию в цикле
    foreach ($venue->getSpaces() as $space) {
        print $space->getName() . "\n";
    }
*/