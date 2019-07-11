<?php

namespace Database\Mapper;


class Field {
    protected $name = null;
    protected $operator = null;
    protected $comparisons = array();
    protected $incomplete = false;

    public function __construct( $name ) {
        $this->name = $name;
    }

    public function addExpr( $operator, $value ) {
        $this->comparisons[] = array(
            'name'     => $this->name,
            'operator' => $operator,
            'value'    => $value
        );
    }

    public function getName() {
        return $this->name;
    }

    public function getComparisons(): array {
        return $this->comparisons;
    }

    public function isIncomplete() {
        return empty( $this->comparisons );
    }
}