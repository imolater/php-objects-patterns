<?php

namespace Test\Prototype;

require_once 'autoload.php';

use Prototype\Terrain;
use Prototype\TerrainFactory;
use PHPUnit\Framework\TestCase;

class TerrainFactoryTest extends TestCase {
    private $factory;

    protected function setUp() {
        $this->factory = new TerrainFactory(
            new Terrain\Sea(),
            new Terrain\MarsForest(),
            new Terrain\Plains()
        );
    }

    public function testGetSea() {
        $sea = $this->factory->getSea();
        $anotherSea = $this->factory->getSea();

        self::assertTrue($sea instanceof Terrain\Sea);
        self::assertNotSame($sea, $anotherSea);
    }

    public function testGetPlains() {
        $plains = $this->factory->getPlains();
        $anotherPlains = $this->factory->getPlains();

        self::assertTrue($plains instanceof Terrain\Plains);
        self::assertNotSame($plains, $anotherPlains);
    }

    public function testGetForest() {
        $forest = $this->factory->getForest();
        $anotherForest = $this->factory->getForest();

        self::assertTrue($forest instanceof Terrain\Forest);
        self::assertNotSame($forest, $anotherForest);
    }
}
