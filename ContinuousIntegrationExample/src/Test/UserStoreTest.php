<?php

namespace Test;

require_once 'ApplicationExample/Persist/UserStore.php';

use ApplicationExample\Persist\UserStore;
use PHPUnit\Framework\TestCase;

class UserStoreTest extends TestCase {
    private $store;

    protected function setUp() {
        $this->store = new UserStore();
    }

    /**
     * Проверка получения данных пользователя
     *
     * @throws \Exception
     */
    public function testGetUser() {
        $this->store->addUser( 'bob testovich', 'a@b.com', '12345' );
        $user = $this->store->getUser( 'a@b.com' );
        $this->assertEquals( $user->getMail(), 'a@b.com' );
        $this->assertEquals( $user->getName(), 'bob testovich' );
        $this->assertEquals( $user->getPass(), '12345' );
    }

    public function testAddUser_ShortPass() {
        $this->expectException('Exception');
        $this->store->addUser( 'bob', 'a@b.com', '132' );
    }

    /**
     * Проверка добавления пользователя с существующим email
     *
     * @throws \Exception
     */
    public function testAddUser_Duplicate() {
        try {
            $this->store->addUser('bob williams', 'bob@example.com', '12345');
            $this->store->addUser('bob stevens', 'bob@example.com', '12345');
            $this->fail('Здесь должно было быть вызвано исключение');
        } catch (\Exception $e) {
            $const = $this->logicalAnd(
                $this->isType('string'),
                $this->logicalNot($this->equalTo('bob stevens'))
            );

            $this->assertThat($this->store->getUser('bob@example.com')->getName(), $const);
        }
    }
}