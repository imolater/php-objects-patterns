<?php
/**
 * Шаблон Interpreter
 */

namespace Interpreter;

abstract class Expression {
    private static $keyCount = 0;
    protected $key;

    abstract function interpret( InterpretContext $context );

    public function __construct() {
        $this->key = self::$keyCount++;
    }

    public function getKey() {
        return $this->key;
    }
}

class LiteralExpression extends Expression {
    protected $value;

    public function __construct( $value ) {
        parent::__construct();
        $this->value = $value;
    }

    public function interpret( InterpretContext $context ) {
        $context->set( $this, $this->value );
    }
}

class VariableExpression extends Expression {
    protected $value;

    public function __construct( $key, $value = null ) {
        if ( $key ) {
            $this->key = $key;
        } else {
            parent::__construct();
        }

        $this->value = $value;
    }

    public function interpret( InterpretContext $context ) {
        $context->set( $this, $this->value );
    }

    public function setValue( $value ) {
        $this->value = $value;
    }
}