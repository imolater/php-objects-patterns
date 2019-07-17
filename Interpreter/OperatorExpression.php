<?
/**
 * Шаблон Interpreter
 */

namespace Interpreter;

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

class EqualsOperatorExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult == $rightResult);
    }
}

class BooleanOrOperatorExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult || $rightResult);
    }
}

class BooleanAndOperatorExpression extends OperatorExpression {
    public function doInterpret( InterpretContext $context, $leftResult, $rightResult ) {
        $context->set($this, $leftResult && $rightResult);
    }
}