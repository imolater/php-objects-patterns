<?php

namespace Test\Prototype\Terrain;

require_once 'autoload.php';

use Prototype\TerrainFactory;
use Prototype\Terrain;
use PHPUnit\Framework\TestCase;

class SeaTest extends TestCase {
    private $factory;

    protected function setUp() {
        $this->factory = new TerrainFactory(
            new Terrain\Sea(),
            new Terrain\MarsForest(),
            new Terrain\Plains()
        );
    }

    public function test__clone() {
        // Создаём одно море
        $sea = $this->factory->getSea();
        // Создаём другое море
        $anotherSea = $this->factory->getSea();

        // При клонировании объектов через фабрику, хранимые в
        // объектаха свойства сохранились не как ссылки, а
        // создались правильные копии
        self::assertNotSame($sea->getResource(), $anotherSea->getResource());
    }

    public function testGetResource() {
        $sea = $this->factory->getSea();
        $resource = $sea->getResource();
        $anotherResource = $sea->getResource();

        self::assertSame($resource, $anotherResource);
    }
}
