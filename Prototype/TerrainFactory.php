<?php

namespace Prototype;

/**
 * Class TerrainFactory
 * @package Prototype
 */
class TerrainFactory {
    /**
     * @var Terrain\Sea
     */
    private $sea;
    /**
     * @var Terrain\Forest
     */
    private $forest;
    /**
     * @var Terrain\Plains
     */
    private $plains;

    /**
     * TerrainFactory constructor.
     * @param Terrain\Sea    $sea
     * @param Terrain\Forest $forest
     * @param Terrain\Plains $plains
     */
    public function __construct( Terrain\Sea $sea, Terrain\Forest $forest, Terrain\Plains $plains ) {
        $this->sea = $sea;
        $this->forest = $forest;
        $this->plains = $plains;
    }

    /**
     * @return Terrain\Sea
     */
    public function getSea() {
        return clone $this->sea;
    }

    /**
     * @return Terrain\Forest
     */
    public function getForest() {
        return clone $this->forest;
    }

    /**
     * @return Terrain\Plains
     */
    public function getPlains() {
        return clone $this->plains;
    }
}