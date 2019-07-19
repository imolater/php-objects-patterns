<?php

namespace Test;

require_once 'ApplicationExample/Persist/UserStore.php';
require_once 'ApplicationExample/Util/Validator.php';

use ApplicationExample\Domain\User;
use ApplicationExample\Persist\UserStore;
use ApplicationExample\Util\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase {
    private $validator;

    protected function setUp() {
        $store = new UserStore();
        $store->addUser( 'bob', 'bob@example.com', '12345' );
        $this->validator = new Validator($store);
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
        $store = $this->createMock( 'ApplicationExample\Persist\UserStore' );
        $this->validator = new Validator($store);

        $store->expects( $this->once() )
              ->method( 'notifyPasswordFailure' )
              ->with( 'bob@example.com' );

        $store->expects( $this->once() )
              ->method( 'getUser' )
              ->will( $this->returnValue(
                  new User('bob', 'bob@example.com', 'rightPass')
              ));

        $this->validator->validateUser( 'bob@example.com', 'wrongPass' );
    }
}
