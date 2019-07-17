<?php
/**
 * Шаблон Transaction Script
 */

namespace TransactionScript;

class VenueManager extends Base {
    static $add_venue = "INSERT INTO venue (name) VALUES(?)";
    static $add_space = "INSERT INTO space (name, venue) VALUES(?, ?)";
    static $check_slot = "SELECT id, name FROM event WHERE space = ? AND (start + duration) > ? AND  start < ?";
    static $add_event = "INSERT INTO event (name, space, start, duration) VALUES (?, ?, ?, ?)";

    public function addVenue( $name, $spaces ) {
        $data = [];
        $data[ 'venue' ] = array( $name );
        $this->doStatement( self::$add_venue, $data[ 'venue' ] );
        $venueId = self::$DB->lastInsertId();

        $data[ 'spaces' ] = [];
        foreach ( $spaces as $space ) {
            $values = array( $space, $venueId );
            $this->doStatement( self::$add_space, $values );
            $spaceId = self::$DB->lastInsertId();
            array_unshift( $values, $spaceId );
            $data[ 'spaces' ][] = $values;
        }

        return $data;
    }

    public function addEvent( $name, $space, $start, $duration ) {
        $values = array( $name, $space, ( $start + $duration ) );
        $check = $this->doStatement( self::$check_slot, $values );

        if ( $result = $check->fetch() ) {
            throw new \Exception( 'В данном месте в это время уже проходит другое мероприятие!' );
        }

        $values = array( $name, $space, $start, $duration );
        $this->doStatement( self::$add_event, $values );
    }
}

/* Тесты
    $manager = new TransactionScript\VenueManager();
    $result = $manager->addVenue( 'Зажопинское', array( 'Липовое', 'Лабутеновое' ) );
    print_r( $result );
*/