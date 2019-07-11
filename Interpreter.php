<?
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

abstract class OperatorExpression extends Expression {
    private $leftOperand;
    private $rightOperand;

    public function __construct( Expression $leftOperand, Expression $rightOperand ) {
        parent::__construct();
        $this->leftOperand = $leftOperand;
        $this->rightOperand = $rightOperand;
    }

    public function interpret( InterpretContext $context ) {
        $this->leftOperand->interpret( $context );
        $this->rightOperand->interpret( $context );
        $leftResult = $context->get( $this->leftOperand );
        $rightResult = $context->get( $this->rightOperand );

        $this->doInterpret( $context, $leftResult, $rightResult );
    }

    abstract function doInterpret( InterpretContext $context, $leftResult, $rightResult );
}

class EqualsExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult == $rightResult);
    }
}

class BooleanOrExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult || $rightResult);
    }
}

class BooleanAndExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult && $rightResult);
    }
}