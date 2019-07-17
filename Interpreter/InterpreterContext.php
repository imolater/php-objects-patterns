<?php
/**
 * Шаблон Interpreter
 */

namespace Interpreter;

class InterpretContext {
    private $storage = array();

    public function set( Expression $exp, $value ) {
        $key = $exp->getKey();
        $this->storage[ $key ] = $value;
    }

    public function get( Expression $exp ) {
        $key = $exp->getKey();
        return $this->storage[ $key ];
    }
}