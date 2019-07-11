<?php
/**
 * Шаблоны баз данных - Domain Model
 */

namespace Database\Domain;

use Database\Mapper;

class Venue extends DomainObject {
    private $spaces;

    public function setSpaces( Mapper\SpaceCollection $spaces ): void {
        $this->spaces = $spaces;
    }

    public function getSpaces() {
        if ( is_null( $this->spaces ) )
            $this->spaces = self::getCollection( Space::class );

        return $this->spaces;
    }

    public function addSpace( Space $space ) {
        $this->getSpaces()->add( $space );
        $space->setVenue( $this );
    }
}