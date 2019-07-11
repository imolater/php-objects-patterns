<?php
/**
 * Шаблоны баз данных - Domain Model
 */

namespace Database\Domain;

class Event extends DomainObject {
    private $start;
    private $duration;
    private $space = null;

    public function __construct( $name, int $start, int $duration, $spaceId = null, $id = null, $isNew = false ) {
        parent::__construct( $name, $id, $isNew );

        $this->start = $start;
        $this->duration = $duration;

        if ( !is_null( $spaceId ) )
            $this->space = Space::getMapper()->select( $spaceId );
    }

    /**
     * @param mixed $start
     */
    public function setStart( int $start ): void {
        $this->start = $start;
        $this->markChanged();
    }

    /**
     * @return int
     */
    public function getStart(): int {
        return $this->start;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration( int $duration ): void {
        $this->duration = $duration;
        $this->markChanged();
    }

    /**
     * @return int
     */
    public function getDuration(): int {
        return $this->duration;
    }

    /**
     * @param DomainObject $space
     */
    public function setSpace( DomainObject $space ): void {
        $this->space = $space;
        $this->markChanged();
    }

    /**
     * @return DomainObject|null
     */
    public function getSpace(): ?DomainObject {
        return $this->space;
    }

    /**
     * @return int|null
     */
    public function getSpaceId(): ?int {
        if ( is_null( $this->space ) )
            return null;

        return $this->space->getId();
    }
}