<?php

namespace Database\Mapper;


class IdentityObject {

    /** @var Field|null $curField */
    protected $curField = null;
    protected $fields = array();
    private $allowedFields = array();

    // Конструктору можно передать имя поля, чтобы сразу начать работу с ним,
    // а также массив разрешенных имен полей
    public function __construct( $field = null, array $allowedFields = null ) {
        if ( !is_null( $field ) ) {
            $this->field( $field );
        }

        if ( !is_null( $allowedFields ) ) {
            $this->allowedFields = $allowedFields;
        }
    }

    // Массив зарегистрированных полей
    public function getFields(): array {
        return $this->fields;
    }

    // Массив разрешенных имен полей
    public function getAllowedFields(): array {
        return $this->allowedFields;
    }

    // Добавляем новое поле
    public function field( $name ) {
        // Если поле неполное - генерируем ошибку
        if ( !$this->isVoid() && $this->curField->isIncomplete() ) {
            throw new \Exception( "Поле {$this->curField->getName()} не полное" );
        }

        // Проверяем имя поля на допустимость
        $this->checkFieldAllow( $name );

        // Если мы уже работали с этим полем, то
        // достаём его из хранилища и продолжаем работу с ним
        if ( isset( $this->fields[$name] ) ) {
            $this->curField = $this->fields[$name];
        } else {
            // Если нет, то создаём его объект, сохраняем его и
            // работаем с ним
            $this->curField = new Field( $name );
            $this->fields[$name] = $this->curField;
        }

        // Возвращаем объект для построения цепочки вызовов
        return $this;
    }

    // Проверка на наличие поля
    public function isVoid() {
        return empty( $this->fields );
    }

    // Проверка имени поля на допустимость
    public function checkFieldAllow( $name ) {
        if ( !empty( $this->allowedFields ) && !in_array( $name, $this->allowedFields ) ) {
            $fields = implode( ',', $this->allowedFields );
            throw new \Exception( "{$name} не является полем из списка разрешенных: {$fields}" );
        }
    }

    // Конструктор выражений
    // Получает текущее поле и добавляет оператор и значение к нему
    private function operator( $operator, $value ) {
        // Если пусто, то у нас нет поля для работы
        if ( $this->isVoid() )
            throw new \Exception( 'Поле не определено.' );

        // Добавляем выражение к полю
        $this->curField->addExpr( $operator, $value );
        // Возвращаем объект для построения цепочки вызовов
        return $this;
    }

    /* Конкретные обёртки над operator */
    public function eq( $value ) {
        return $this->operator( '=', $value );
    }

    public function lt( $value ) {
        return $this->operator( '<', $value );
    }

    public function gt( $value ) {
        return $this->operator( '>', $value );
    }

    // Возвращает ассоциативный массив всех выражений примененных к полю
    public function getComparisons() {
        $comparisons = array();

        foreach ( $this->fields as $key => $field )
            $comparisons = array_merge( $comparisons, $field->getComparisons() );

        return $comparisons;
    }
}

class VenueIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array('id', 'name');
        parent::__construct( $field, $allowedFields );
    }
}

class SpaceIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array('id', 'name', 'venue');
        parent::__construct( $field, $allowedFields );
    }
}

class EventIdentityObject extends IdentityObject {
    public function __construct( $field = null ) {
        $allowedFields = array('id', 'name', 'space', 'start', 'duration');
        parent::__construct( $field, $allowedFields );
    }
}

/* Тесты
    $idObj = new \Database\Mapper\IdentityObject();

    // Пример стандартной работы
    $idObj
        ->field( 'name' )->eq( 'Good Show' )
        ->field( 'start' )->lt( time() )->gt( time() + ( 24 * 60 * 60 ) );

    // Пример продолжения работы с полем
    $idObj
        ->field( 'name' )->eq( 'Good Show' )
        ->field( 'start' )->lt( time() )->gt( time() + ( 24 * 60 * 60 ) )
        ->field('name')->eq('Барбарис');

    print_r( $idObj->getComparisons() );

    // Ошибка - Неполное поле
    $idObj->field( 'name' )->field( 'start' )->lt( time() );

    // Ошибка - Поле не является разрешенным
    $idObj2 = new \Database\Mapper\VenueIdentityObject();
    $idObj2->field('test')->eq('test');
*/