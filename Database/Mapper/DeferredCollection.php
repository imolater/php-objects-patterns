<?php
/**
 * Шаблоны баз данных
 */

namespace Database\Mapper;

class VenueDeferredCollection extends VenueCollection {
    private $stmt;
    private $stmtValues;
    private $run = false;

    public function __construct( $raw, Mapper $mapper, \PDOStatement $stmt ) {
        parent::__construct( null, $mapper );
        $this->stmt = $stmt;
        $this->stmtValues = $raw;
    }

    protected function notifyAccess() {
        if ( !$this->run ) {
            $this->stmt->execute( $this->stmtValues );
            $this->raw = $this->stmt->fetchAll( \PDO::FETCH_ASSOC );
            $this->count = count( $this->raw );
        }

        $this->run = true;
    }
}

class SpaceDeferredCollection extends SpaceCollection {
    private $stmt;
    private $stmtValues;
    private $run = false;

    public function __construct( $raw, Mapper $mapper, \PDOStatement $stmt ) {
        parent::__construct( null, $mapper );
        $this->stmt = $stmt;
        $this->stmtValues = $raw;
    }

    protected function notifyAccess() {
        if ( !$this->run ) {
            $this->stmt->execute( $this->stmtValues );
            $this->raw = $this->stmt->fetchAll( \PDO::FETCH_ASSOC );
            $this->count = count( $this->raw );
        }

        $this->run = true;
    }
}

class EventDeferredCollection extends EventCollection {
    private $stmt;
    private $stmtValues;
    private $run = false;

    /* При создании коллекции с отложенной загрузкой мы сохраняем
     * Mapper нужного типа для создания объектов
     * Данные для запроса к БД
     * Сам запрос к БД
     */
    public function __construct( $raw, Mapper $mapper, \PDOStatement $stmt ) {
        parent::__construct( null, $mapper );
        $this->stmt = $stmt;
        $this->stmtValues = $raw;
    }

    // Функция активирует отложенную загрузку
    protected function notifyAccess() {
        if ( !$this->run ) {
            // Выполеняем запрос
            $this->stmt->execute( $this->stmtValues );
            $this->raw = $this->stmt->fetchAll( \PDO::FETCH_ASSOC );
            // Записываем количество для итератора
            $this->count = count( $this->raw );
        }

        // Отключаем метод, после первого использования
        $this->run = true;
    }
}