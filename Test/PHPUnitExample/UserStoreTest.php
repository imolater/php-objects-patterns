<?php

namespace Test\PHPUnitExample;

require_once 'autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnitExample\UserStore;

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
        $this->assertEquals( $user[ 'mail' ], 'a@b.com' );
        $this->assertEquals( $user[ 'name' ], 'bob testovich' );
        $this->assertEquals( $user[ 'pass' ], '12345' );
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
                $this->logicalNot($this->contains('bob stevens')),
                $this->isType('array')
            );

            $this->assertThat($this->store->getUser('bob@example.com'), $const);
        }
    }
}