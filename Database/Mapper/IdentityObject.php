<?php

namespace Database\Mapper;


class IdentityObject {

    /** @var Field|null $curField */
    protected $curField = null;
    protected $fields = array();
    private $and = null;
    private $allowedFields = array();

    public function __construct( $field = null, array $allowedFields = null ) {
        if ( ! is_null( $field ) ) {
            $this->field( $field );
        }

        if ( ! is_null( $allowedFields ) ) {
            $this->allowedFields = $allowedFields;
        }
    }

    /**
     * @return array
     */
    public function getFields(): array {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getAllowedFields(): array {
        return $this->allowedFields;
    }

    public function field( $name ) {
        if ( ! $this->isVoid() && $this->curField->isIncomplete() ) {
            throw new \Exception( "Поле {$this->curField->getName()} не полное" );
        }

        $this->checkFieldAllow( $name );

        if ( isset( $this->fields[ $name ] ) ) {
            $this->curField = $this->fields[ $name ];
        } else {
            $this->curField = new Field( $name );
            $this->fields[ $name ] = $this->curField;
        }

        return $this;
    }

    public function isVoid() {
        return empty( $this->fields );
    }

    public function checkFieldAllow( $name ) {
        if ( ! in_array( $name, $this->allowedFields ) ) {
            $fields = implode( ',', $this->allowedFields );
            throw new \Exception( "{$name} не является полем из списка разрешенных: {$fields}" );
        }
    }

    private function operator( $operator, $value ) {
        if ( $this->isVoid() )
            throw new \Exception( 'Поле не определено.' );

        $this->curField->addExpr( $operator, $value );
        return $this;
    }

    public function eq( $value ) {
        return $this->operator( '=', $value );
    }

    public function lt( $value ) {
        return $this->operator( '<', $value );
    }

    public function gt( $value ) {
        return $this->operator( '>', $value );
    }

    public function getComparisons() {
        $comparisons = array();

        foreach ( $this->fields as $key => $field )
            $comparisons[ $key ] = reset( $field->getComparisons() );

        return $comparisons;
    }
}

class VenueIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array( 'id', 'name' );
        parent::__construct( $field, $allowedFields );
    }
}

class SpaceIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array( 'id', 'name', 'venue' );
        parent::__construct( $field, $allowedFields );
    }
}

class EventIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array( 'id', 'name', 'space', 'start', 'duration' );
        parent::__construct( $field, $allowedFields );
    }
}