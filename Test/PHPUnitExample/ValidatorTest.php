<?php

namespace Test\PHPUnitExample;

use PHPUnit\Framework\TestCase;
use PHPUnitExample\UserStore;
use PHPUnitExample\Validator;

class ValidatorTest extends TestCase {
    private $validator;

    protected function setUp() {
        $store = new UserStore();
        $store->addUser( 'bob', 'bob@example.com', '12345' );
        $this->validator = new Validator( $store );
    }

    public function testValidateUser_CorrectPass() {
        $this->assertTrue(
            $this->validator->validateUser( 'bob@example.com', '12345' ),
            'Ожидалась успешная валидация пользователя'
        );
    }

    /**
     * Проверка неудачной валидации пользователя
     *
     * Мы создаём имитацию (MockObject) объекта UserStore, откуда
     * берёт данные объект Validator. Далее мы говорим ей, что ожидаем
     * единственный вызов метода notifyPasswordFailure с аргументом
     * mail = bob@example.com, а единственный вызов метода
     * getUser с указанием вернуть конкретный массив значений.
     * Метод validateUser использует метод getUser, чтобы достать данные
     * из хранилища, и дастаёт имитированные нами данные. Пароли не совпадают,
     * происходит вызов метода notifyPasswordFailure с заданным нами email.
     *
     * @throws \ReflectionException
     */
    public function testValidateUser__WrongPass() {
        $store = $this->createMock( 'PHPUnitExample\UserStore' );
        $this->validator = new Validator( $store );

        $store->expects( $this->once() )
              ->method( 'notifyPasswordFailure' )
              ->with( 'bob@example.com' );

        $store->expects( $this->once() )
              ->method( 'getUser' )
              ->will( $this->returnValue( array(
                  'name' => 'bob',
                  'mail' => 'bob@example.com',
                  'pass' => 'right'
              ) ) );

        $this->validator->validateUser( 'bob@example.com', 'wrong' );
    }
}
