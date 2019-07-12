<?php

namespace Database\Mapper;

class Field {
    protected $name = null;
    protected $comparisons = array();

    // Устанавливает имя поля, например name
    public function __construct( $name ) {
        $this->name = $name;
    }

    // Добавялет оператор и значение для проверки
    // (> 40, например) и помещает его в массив
    // который в будущем склеится в один запрос
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

    // Если мы создали эклемпляр Field, значит есть
    // данные для сравнения, но если $comparisons пустой
    // то значит наше поле ещё не готово
    public function isIncomplete() {
        return empty( $this->comparisons );
    }
}