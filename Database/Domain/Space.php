<?php
/**
 * Шаблоны баз данных - Domain Model
 */

namespace Database\Domain;

use Database\Mapper;

class Space extends DomainObject {
    private $venue = null;
    private $events;

    public function __construct( $name, $venueId = null, $id = null, $isNew = false ) {
        parent::__construct( $name, $id, $isNew );

        if ( !is_null( $venueId ) )
            $this->venue = Venue::getMapper()->select( $venueId );
    }

    public function setVenue( Venue $venue ) {
        $this->venue = $venue;
        $this->markChanged();
    }

    public function getVenue() {
        return $this->venue;
    }

    /**
     * @return int|null
     */
    public function getVenueId(): ?int {
        if ( is_null( $this->venue ) )
            return null;

        return $this->venue->getId();
    }

    public function setEvents( Mapper\EventCollection $events ): void {
        $this->events = $events;
    }

    public function getEvents() {
        if ( is_null( $this->events ) )
            $this->events = self::getCollection( Event::class );

        return $this->events;
    }

    public function addEvent( Event $event ) {
        $this->getEvents()->add( $event );
        $event->setSpace( $this );
    }
}