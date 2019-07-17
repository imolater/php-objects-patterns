<?php
/**
 * Шаблон Transaction Script
 */

namespace TransactionScript;

use Registry\ApplicationRegistry;

abstract class Base {
    static $DB;
    static $statements = [];

    public function __construct() {
        $db = ApplicationRegistry::getDSN();
        if ( is_null( $db ) ) {
            throw new \Exception( 'DSN не определён!' );
        }

        self::$DB = new \PDO( $db['dsn'], $db['login'], $db['password'] );
        self::$DB->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    }

    private function prepareStatement( $statement ) {
        if ( isset( self::$statements[ $statement ] ) ) {
            return self::$statements[ $statement ];
        }

        $handler = self::$DB->prepare( $statement );
        self::$statements[ $statement ] = $handler;

        return $handler;
    }

    public function doStatement( $statement, $values ) {
        $statement = $this->prepareStatement( $statement );
        $statement->closeCursor();
        $statement->execute( $values );

        return $statement;
    }
}