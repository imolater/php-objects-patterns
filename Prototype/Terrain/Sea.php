<?php

namespace Prototype\Terrain;


use Prototype\Resource\FishResource;

class Sea {
    private $navigability;
    private $resource;

    /**
     * Sea constructor.
     * @param int $navigability
     */
    public function __construct( int $navigability = 0 ) {
        $this->navigability = $navigability;
        $this->resource = new FishResource();
    }

    public function __clone() {
        $this->resource = clone $this->resource;
    }

    /**
     * @return FishResource
     */
    public function getResource(): FishResource {
        return $this->resource;
    }
}

class EarthSea extends Sea {}

class MarsSea extends Sea {}